<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bundle_id',
        'guest_email',
        'recipient_phone',
        'status',
        'cost',
        'cost_price',
        'profit',
        'reference',
        'payment_method',
        'payment_reference',
        'source',
        'response_data',
    ];

    protected $casts = [
        'response_data' => 'array',
        'cost' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::created(function ($order) {
            $savedEventsJson = \App\Models\Setting::where('key', 'webhook_events')->first()?->value ?? '[]';
            $savedEvents = json_decode($savedEventsJson, true);
            
            if (is_array($savedEvents) && in_array('Order Created', $savedEvents)) {
                \App\Jobs\SendWebhookJob::dispatch('Order Created', $order->toArray());
            }
        });

        static::updated(function ($order) {
            if ($order->isDirty('status')) {
                $savedEventsJson = \App\Models\Setting::where('key', 'webhook_events')->first()?->value ?? '[]';
                $savedEvents = json_decode($savedEventsJson, true);
                if (!is_array($savedEvents)) {
                    $savedEvents = [];
                }

                if ($order->status === 'processing' && in_array('Order Processing', $savedEvents)) {
                    \App\Jobs\SendWebhookJob::dispatch('Order Processing', $order->toArray());
                } elseif ($order->status === 'delivered' && in_array('Order Completed', $savedEvents)) {
                    \App\Jobs\SendWebhookJob::dispatch('Order Completed', $order->toArray());
                } elseif ($order->status === 'failed' && in_array('Order Failed', $savedEvents)) {
                    \App\Jobs\SendWebhookJob::dispatch('Order Failed', $order->toArray());
                }

                if ($order->status === 'failed') {
                    $order->refund();
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function complete($responseData = null)
    {
        if ($this->status === 'delivered') {
            return;
        }

        $this->update([
            'status' => 'delivered',
            'response_data' => $responseData
        ]);

        $user = $this->user;
        if ($user->referred_by_id) {
            $referrer = $user->referrer;
            $commissionAmount = 0;

            if ($referrer->isReseller()) {
                // Calculate profit based on reseller's cost
                $bundle = $this->bundle;
                $rolePrices = $bundle->role_prices ?: [];
                $resellerCost = $rolePrices[$referrer->role] ?? $bundle->price;

                // Commission is the difference between the selling price and the reseller's cost
                // If this is a storefront order, the "selling price" relative to the parent is the reseller's cost_price
                $basePrice = ($this->source === 'storefront') ? $this->cost_price : $this->cost;
                $commissionAmount = $basePrice - $resellerCost;

                // Ensure it's not negative (safety check)
                $commissionAmount = max(0, $commissionAmount);
            } else {
                // Standard 5% commission for normal user referrals
                $commissionAmount = $this->cost * 0.05;
            }

            if ($commissionAmount > 0) {
                Commission::create([
                    'user_id' => $referrer->id,
                    'referred_user_id' => $user->id,
                    'order_id' => $this->id,
                    'amount' => $commissionAmount,
                    'status' => 'earned',
                ]);

                $referrer->increment('wallet_balance', $commissionAmount);

                $referrer->transactions()->create([
                    'amount' => $commissionAmount,
                    'type' => 'credit',
                    'reference' => 'COM-' . strtoupper(\Illuminate\Support\Str::random(10)),
                    'status' => 'success',
                    'description' => "Reseller profit from order #{$this->reference} by {$user->name}",
                ]);
            }
        }
    }

    /**
     * Refund the customer/reseller for a failed order.
     */
    public function refund()
    {
        // 1. Determine if the order has been paid/charged
        $isPaid = false;
        if ($this->payment_method === 'wallet') {
            $isPaid = true;
        } elseif ($this->payment_reference) {
            $isPaid = true;
        } else {
            $originalStatus = $this->getOriginal('status');
            if (in_array($originalStatus, ['validation', 'processing', 'delivered'])) {
                $isPaid = true;
            }
        }

        if (!$isPaid) {
            \Illuminate\Support\Facades\Log::info("Refund skipped for Order #{$this->id}: Order was not paid.");
            return;
        }

        // 2. Check if already refunded to avoid double refunding
        $alreadyRefunded = \App\Models\Transaction::where('order_id', $this->id)
            ->where(function ($q) {
                $q->where('description', 'like', '%Refund%')
                  ->orWhere('description', 'like', '%Reversal%');
            })->exists();

        if ($alreadyRefunded) {
            \Illuminate\Support\Facades\Log::info("Refund skipped for Order #{$this->id}: Already refunded/reversed.");
            return;
        }

        \Illuminate\Support\Facades\DB::transaction(function () {
            // 3. Handle Storefront vs Direct Customer Order
            if ($this->source === 'storefront') {
                $reseller = $this->user;
                if ($reseller) {
                    // Refund wholesale cost (credit)
                    $reseller->increment('wallet_balance', (float) $this->cost_price);
                    $reseller->transactions()->create([
                        'order_id' => $this->id,
                        'amount' => $this->cost_price,
                        'type' => 'credit',
                        'reference' => 'WHR-' . strtoupper(\Illuminate\Support\Str::random(10)),
                        'status' => 'success',
                        'description' => "Wholesale Refund for failed storefront order #{$this->reference}",
                        'metadata' => [
                            'reason' => 'Order status returned failed',
                            'original_status' => $this->getOriginal('status'),
                        ],
                    ]);

                    // Reverse storefront revenue (debit)
                    $reseller->decrement('wallet_balance', (float) $this->cost);
                    $reseller->transactions()->create([
                        'order_id' => $this->id,
                        'amount' => $this->cost,
                        'type' => 'debit',
                        'reference' => 'SRV-' . strtoupper(\Illuminate\Support\Str::random(10)),
                        'status' => 'success',
                        'description' => "Reversal of storefront revenue for failed order #{$this->reference}",
                        'metadata' => [
                            'reason' => 'Order status returned failed',
                            'original_status' => $this->getOriginal('status'),
                        ],
                    ]);

                    \Illuminate\Support\Facades\Log::info("Processed storefront refund/reversal for Reseller #{$reseller->id} on failed order #{$this->id}");
                }
            } else {
                $user = $this->user;
                if ($user) {
                    // Direct customer refund (credit)
                    $user->increment('wallet_balance', (float) $this->cost);
                    $user->transactions()->create([
                        'order_id' => $this->id,
                        'amount' => $this->cost,
                        'type' => 'credit',
                        'reference' => 'REF-' . strtoupper(\Illuminate\Support\Str::random(10)),
                        'status' => 'success',
                        'description' => "Refund for failed order #{$this->reference}",
                        'metadata' => [
                            'reason' => 'Order status returned failed',
                            'original_status' => $this->getOriginal('status'),
                        ],
                    ]);

                    \Illuminate\Support\Facades\Log::info("Refunded GHS {$this->cost} to User #{$user->id} for failed order #{$this->id}");
                }
            }

            // 4. Reverse any referral commissions paid for this order
            $commissions = \App\Models\Commission::where('order_id', $this->id)->get();
            /** @var \App\Models\Commission $commission */
            foreach ($commissions as $commission) {
                $referrer = $commission->user;
                if ($referrer && $commission->status === 'earned') {
                    $referrer->decrement('wallet_balance', (float) $commission->amount);
                    $referrer->transactions()->create([
                        'order_id' => $this->id,
                        'amount' => $commission->amount,
                        'type' => 'debit',
                        'reference' => 'REV-COM-' . strtoupper(\Illuminate\Support\Str::random(10)),
                        'status' => 'success',
                        'description' => "Reversal of reseller commission for failed order #{$this->reference}",
                    ]);
                    $commission->update(['status' => 'cancelled']);
                    \Illuminate\Support\Facades\Log::info("Reverted commission of GHS {$commission->amount} for referrer #{$referrer->id}");
                }
            }
        });
    }

    public static function generateReference($prefix = 'ORD-')
    {
        $dateStr = date('Ymd');
        $nextId = (static::max('id') ?? 0) + 1;

        do {
            $counter = str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $ref = $prefix . $dateStr . '-' . $counter;
            $nextId++;
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }
}
