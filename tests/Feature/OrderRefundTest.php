<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\Bundle;
use App\Services\ApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderRefundTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed dummy settings if needed
        DB::table('settings')->insertOrIgnore([
            ['key' => 'webhook_events', 'value' => json_encode(['Order Failed', 'Order Completed'])]
        ]);
    }

    /**
     * Test direct order wallet refund is processed when order status transitions to failed.
     */
    public function test_direct_order_wallet_refund_on_failure()
    {
        $user = User::factory()->create(['wallet_balance' => 100.00]);
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-111111111',
            'payment_reference' => 'TRX-111111111',
            'source' => 'dashboard'
        ]);

        $this->assertEquals(100.00, $user->fresh()->wallet_balance);

        // Transition order status to failed
        $order->update(['status' => 'failed']);

        // Assert customer was refunded the cost (30.00)
        $this->assertEquals(130.00, $user->fresh()->wallet_balance);

        // Assert transaction log was created
        $transaction = Transaction::where('order_id', $order->id)
            ->where('user_id', $user->id)
            ->where('type', 'credit')
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals(30.00, $transaction->amount);
        $this->assertStringContainsString('Refund for failed order', $transaction->description);
    }

    /**
     * Test storefront guest reseller refund and revenue reversal on failure.
     */
    public function test_storefront_guest_reseller_refund_on_failure()
    {
        $reseller = User::factory()->create(['wallet_balance' => 100.00, 'role' => User::ROLE_RETAIL_SELLER]);
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 50.00,
            'cost_price' => 40.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        // In storefront guest purchases, user_id is the reseller
        $order = Order::create([
            'user_id' => $reseller->id,
            'bundle_id' => $bundle->id,
            'cost' => 50.00, // guest retail price
            'cost_price' => 40.00, // reseller wholesale cost
            'status' => 'validation',
            'payment_method' => 'paystack',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-222222222',
            'payment_reference' => 'TRX-222222222',
            'source' => 'storefront'
        ]);

        $this->assertEquals(100.00, $reseller->fresh()->wallet_balance);

        // Transition to failed
        $order->update(['status' => 'failed']);

        // Wholesale cost refund: +40.00
        // Revenue reversal: -50.00
        // Net change: -10.00 => balance 90.00
        $this->assertEquals(90.00, $reseller->fresh()->wallet_balance);

        // Assert wholesale refund transaction
        $creditTx = Transaction::where('order_id', $order->id)
            ->where('user_id', $reseller->id)
            ->where('type', 'credit')
            ->first();
        $this->assertNotNull($creditTx);
        $this->assertEquals(40.00, $creditTx->amount);
        $this->assertStringContainsString('Wholesale Refund', $creditTx->description);

        // Assert revenue reversal transaction
        $debitTx = Transaction::where('order_id', $order->id)
            ->where('user_id', $reseller->id)
            ->where('type', 'debit')
            ->first();
        $this->assertNotNull($debitTx);
        $this->assertEquals(50.00, $debitTx->amount);
        $this->assertStringContainsString('Reversal of storefront revenue', $debitTx->description);
    }

    /**
     * Test commission reversal if the order completed and then subsequently failed.
     */
    public function test_referrer_commission_reversal_on_failure()
    {
        $referrer = User::factory()->create([
            'wallet_balance' => 100.00,
            'role' => User::ROLE_RETAIL_SELLER
        ]);
        $referred = User::factory()->create([
            'wallet_balance' => 50.00,
            'referred_by_id' => $referrer->id
        ]);
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true,
            'role_prices' => [
                User::ROLE_RETAIL_SELLER => 20.00
            ]
        ]);

        $order = Order::create([
            'user_id' => $referred->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-333333333',
            'payment_reference' => 'TRX-333333333',
            'source' => 'dashboard'
        ]);

        // Complete order to award referral commission
        // Referrer gets commission = 30.00 - 20.00 = 10.00.
        $order->complete();

        $this->assertEquals('delivered', $order->fresh()->status);
        $this->assertEquals(110.00, $referrer->fresh()->wallet_balance);

        $commission = Commission::where('order_id', $order->id)->first();
        $this->assertNotNull($commission);
        $this->assertEquals('earned', $commission->status);
        $this->assertEquals(10.00, $commission->amount);

        // Now, update order status to failed
        $order->update(['status' => 'failed']);

        // Customer gets refunded order cost (30.00)
        $this->assertEquals(80.00, $referred->fresh()->wallet_balance); // 50.00 + 30.00 = 80.00

        // Referrer gets commission reversed (10.00 deducted)
        $this->assertEquals(100.00, $referrer->fresh()->wallet_balance);

        // Commission status set to cancelled
        $this->assertEquals('cancelled', $commission->fresh()->status);

        // Reversal transaction created
        $revTx = Transaction::where('order_id', $order->id)
            ->where('user_id', $referrer->id)
            ->where('type', 'debit')
            ->where('description', 'like', '%Reversal of reseller commission%')
            ->first();
        $this->assertNotNull($revTx);
        $this->assertEquals(10.00, $revTx->amount);
    }

    /**
     * Test double refund prevention.
     */
    public function test_double_refund_prevention()
    {
        $user = User::factory()->create(['wallet_balance' => 100.00]);
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-444444444',
            'payment_reference' => 'TRX-444444444',
            'source' => 'dashboard'
        ]);

        // First status update to failed
        $order->update(['status' => 'failed']);
        $this->assertEquals(130.00, $user->fresh()->wallet_balance);

        // Simulate second status update to failed (should be skipped)
        $order->refund();
        $this->assertEquals(130.00, $user->fresh()->wallet_balance);

        // Assert only one transaction was created
        $txCount = Transaction::where('order_id', $order->id)->count();
        $this->assertEquals(1, $txCount);
    }

    /**
     * Test Webhook Controller fetches external status and maps validating/pending correctly.
     */
    public function test_webhook_status_fetching_and_mapping()
    {
        $user = User::factory()->create(['wallet_balance' => 100.00]);
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-555555555',
            'source' => 'dashboard'
        ]);

        // 1. Mock ApiService to return 'pending' (should map to 'validation')
        $this->mock(ApiService::class, function ($mock) {
            $mock->shouldReceive('getProviderForOrder')->andReturn(null);
            $mock->shouldReceive('fetchExternalStatus')->once()->andReturn([
                'status' => 'pending',
                'response' => ['status' => 'pending']
            ]);
        });

        $response = $this->postJson('/api/webhooks/incoming', [
            'reference' => 'ORD-555555555',
            'status' => 'processing' // payload is different, should be overridden by verified external status 'pending'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('validation', $order->fresh()->status);

        // 2. Mock ApiService to return 'processing' (should map to 'processing')
        $this->mock(ApiService::class, function ($mock) {
            $mock->shouldReceive('getProviderForOrder')->andReturn(null);
            $mock->shouldReceive('fetchExternalStatus')->once()->andReturn([
                'status' => 'processing',
                'response' => ['status' => 'processing']
            ]);
        });

        $response = $this->postJson('/api/webhooks/incoming', [
            'reference' => 'ORD-555555555'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('processing', $order->fresh()->status);

        // 3. Mock ApiService to return 'failed' (should map to 'failed' and execute refund)
        $this->mock(ApiService::class, function ($mock) {
            $mock->shouldReceive('getProviderForOrder')->andReturn(null);
            $mock->shouldReceive('fetchExternalStatus')->once()->andReturn([
                'status' => 'failed',
                'response' => ['status' => 'failed']
            ]);
        });

        $response = $this->postJson('/api/webhooks/incoming', [
            'reference' => 'ORD-555555555'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('failed', $order->fresh()->status);
        $this->assertEquals(130.00, $user->fresh()->wallet_balance);
    }

    /**
     * Test admin bulk status update.
     */
    public function test_admin_bulk_status_update()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user1 = User::factory()->create(['wallet_balance' => 100.00, 'is_active' => true]);
        $user2 = User::factory()->create(['wallet_balance' => 100.00, 'is_active' => true]);

        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        $order1 = Order::create([
            'user_id' => $user1->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-B1',
            'source' => 'dashboard'
        ]);

        $order2 = Order::create([
            'user_id' => $user2->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0547654321',
            'reference' => 'ORD-B2',
            'source' => 'dashboard'
        ]);

        // Call bulk status update to 'processing'
        $response = $this->actingAs($admin)
            ->withSession(['admin_verified' => true])
            ->postJson('/admin/orders/bulk-action', [
                'action' => 'status_update',
                'order_ids' => [$order1->id, $order2->id],
                'status' => 'processing'
            ]);

        $response->assertStatus(200);
        $this->assertEquals('processing', $order1->fresh()->status);
        $this->assertEquals('processing', $order2->fresh()->status);

        // Call bulk status update to 'failed' (should trigger refund)
        $response = $this->actingAs($admin)
            ->withSession(['admin_verified' => true])
            ->postJson('/admin/orders/bulk-action', [
                'action' => 'status_update',
                'order_ids' => [$order1->id, $order2->id],
                'status' => 'failed'
            ]);

        $response->assertStatus(200);
        $this->assertEquals('failed', $order1->fresh()->status);
        $this->assertEquals('failed', $order2->fresh()->status);
        $this->assertEquals(130.00, $user1->fresh()->wallet_balance);
        $this->assertEquals(130.00, $user2->fresh()->wallet_balance);
    }

    /**
     * Test admin bulk deletion.
     */
    public function test_admin_bulk_delete()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['is_active' => true]);
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        $order1 = Order::create([
            'user_id' => $user->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0541234567',
            'reference' => 'ORD-D1',
            'source' => 'dashboard'
        ]);

        $order2 = Order::create([
            'user_id' => $user->id,
            'bundle_id' => $bundle->id,
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'recipient_phone' => '0547654321',
            'reference' => 'ORD-D2',
            'source' => 'dashboard'
        ]);

        // Call bulk delete
        $response = $this->actingAs($admin)
            ->withSession(['admin_verified' => true])
            ->postJson('/admin/orders/bulk-action', [
                'action' => 'delete',
                'order_ids' => [$order1->id, $order2->id]
            ]);

        $response->assertStatus(200);
        $this->assertNull(Order::find($order1->id));
        $this->assertNull(Order::find($order2->id));
    }
}
