<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bundle;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin order creation form loads successfully.
     */
    public function test_admin_order_creation_form_loads_successfully()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        
        // Create some bundles to list
        Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        Bundle::create([
            'network' => 'Telecel',
            'name' => 'Telecel 2GB',
            'price' => 50.00,
            'cost_price' => 35.00,
            'data_amount' => '2GB',
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['admin_verified' => true])
            ->get('/admin/orders/create');

        $response->assertStatus(200);
        $response->assertViewHas('bundles');
        $response->assertViewHas('users');
    }

    /**
     * Test admin can store manual order.
     */
    public function test_admin_can_store_manual_order()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $customer = User::factory()->create(['is_active' => true]);
        
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['admin_verified' => true])
            ->post('/admin/orders/store', [
                'user_id' => $customer->id,
                'bundle_id' => $bundle->id,
                'recipient_phone' => '0541234567', // Valid Ghana MTN phone number pattern
                'status' => 'processing',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'bundle_id' => $bundle->id,
            'recipient_phone' => '0541234567',
            'status' => 'processing',
            'cost' => 30.00,
        ]);
    }

    /**
     * Test that placed orders of all statuses show up in admin order management.
     */
    public function test_admin_order_management_lists_orders()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $customer = User::factory()->create(['is_active' => true]);
        
        $bundle = Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 1GB',
            'price' => 30.00,
            'cost_price' => 20.00,
            'data_amount' => '1GB',
            'is_active' => true
        ]);

        // Create 3 orders with different statuses
        $order1 = Order::create([
            'user_id' => $customer->id,
            'bundle_id' => $bundle->id,
            'recipient_phone' => '0541234567',
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'validation',
            'payment_method' => 'wallet',
            'reference' => 'ORD-VAL123',
            'source' => 'dashboard'
        ]);

        $order2 = Order::create([
            'user_id' => $customer->id,
            'bundle_id' => $bundle->id,
            'recipient_phone' => '0541234567',
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'pending_payment',
            'payment_method' => 'paystack',
            'reference' => 'ORD-PEND123',
            'source' => 'dashboard'
        ]);

        $order3 = Order::create([
            'user_id' => $customer->id,
            'bundle_id' => $bundle->id,
            'recipient_phone' => '0541234567',
            'cost' => 30.00,
            'cost_price' => 20.00,
            'status' => 'awaiting_transfer',
            'payment_method' => 'transfer',
            'reference' => 'ORD-TRANS123',
            'source' => 'dashboard'
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['admin_verified' => true])
            ->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertViewHas('orders');
        $ordersCollection = $response->viewData('orders');
        
        // Assert all 3 orders are retrieved
        $this->assertCount(3, $ordersCollection);
        
        $references = $ordersCollection->pluck('reference')->toArray();
        $this->assertContains('ORD-VAL123', $references);
        $this->assertContains('ORD-PEND123', $references);
        $this->assertContains('ORD-TRANS123', $references);
    }
}

