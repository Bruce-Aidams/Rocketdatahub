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
