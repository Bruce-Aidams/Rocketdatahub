<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\ApiProvider;
use App\Services\ApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync-status {--daemon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync processing order status with external vendor every 5 seconds';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daemon = $this->option('daemon');
        $this->info("Starting Order Status Sync Daemon (5-second intervals)...");

        $startTime = time();
        while (true) {
            try {
                // 1. Process queued jobs (like ProcessOrder for new validating orders and SendWebhookJob)
                // This ensures orders don't get stuck in 'validation' if a worker isn't running
                \Illuminate\Support\Facades\Artisan::call('queue:work', [
                    '--stop-when-empty' => true,
                    '--timeout' => 60,
                    '--tries' => 1
                ]);

                // 2. Explicitly pick up any stuck 'validation' orders
                $this->processNewOrders();

                // 3. Sync processing orders with external vendor
                $this->syncOrders();
            } catch (\Exception $e) {
                $this->error("Sync error: " . $e->getMessage());
            }

            // Sleep for 5 seconds
            sleep(5);

            // If we run via standard cron scheduler (without daemon flag), exit after 55 seconds to prevent overlap
            if (!$daemon && (time() - $startTime >= 55)) {
                break;
            }
        }
    }

    /**
     * Perform the order status synchronization
     */
    private function syncOrders()
    {
        $orders = Order::where('status', 'processing')->get();

        if ($orders->isEmpty()) {
            return;
        }

        // Group by provider to minimize external API hits
        $providerOrders = $orders->groupBy(function ($order) {
            $apiService = app(ApiService::class);
            $provider = $apiService->getProviderForOrder($order);
            return $provider ? $provider->id : null;
        });

        foreach ($providerOrders as $providerId => $ordersList) {
            if (!$providerId) continue;

            $provider = ApiProvider::find($providerId);
            if (!$provider) continue;

            // Fetch recent orders from the provider
            $headers = $provider->request_headers ?? [];
            if (!is_array($headers)) $headers = [];

            // Replace placeholders in headers (like api_key)
            $apiKey = $provider->api_key;
            if (!empty($apiKey) && env($apiKey) !== null) {
                $apiKey = env($apiKey);
            }
            foreach ($headers as $key => $value) {
                $headers[$key] = str_replace(['{api_key}', '{{api_key}}'], $apiKey, $value);
            }

            try {
                $response = Http::withHeaders($headers)
                    ->when(app()->environment('local'), function ($http) {
                        return $http->withoutVerifying();
                    })
                    ->get($provider->base_url);

                if (!$response->successful()) {
                    Log::warning("Sync: Failed to fetch orders from {$provider->name}");
                    continue;
                }

                $responseData = $response->json();
                $externalOrders = $responseData['orders'] ?? $responseData['data'] ?? [];

                if (empty($externalOrders)) {
                    continue;
                }

                foreach ($ordersList as $order) {
                    // Extract saved external ID
                    $extOrderId = $order->response_data['raw_response']['order_id'] ?? null;
                    
                    // Match external order
                    $matched = null;
                    foreach ($externalOrders as $extOrder) {
                        if ($extOrderId && $extOrder['id'] == $extOrderId) {
                            $matched = $extOrder;
                            break;
                        }
                        
                        // Fallback matching: phone and amount
                        $phoneMatch = ($extOrder['recipient_msisdn'] ?? '') == $order->recipient_phone;
                        $amountMatch = (float)($extOrder['amount'] ?? 0) == (float)$order->cost;
                        if ($phoneMatch && $amountMatch) {
                            $matched = $extOrder;
                            break;
                        }
                    }

                    if ($matched) {
                        $vendorStatus = strtolower($matched['status'] ?? 'pending');
                        
                        if (in_array($vendorStatus, ['completed', 'successful', 'delivered', 'success'])) {
                            $order->complete(array_merge($order->response_data ?? [], ['sync_response' => $matched]));
                            Log::info("Order #{$order->id} successfully synced and marked as delivered.");
                        } elseif (in_array($vendorStatus, ['failed', 'error', 'reversed', 'refunded'])) {
                            $order->update([
                                'status' => 'failed',
                                'response_data' => array_merge($order->response_data ?? [], ['sync_response' => $matched])
                            ]);
                            Log::info("Order #{$order->id} successfully synced and marked as failed.");
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Sync error for provider {$provider->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Explicitly process any orders stuck in validation state
     */
    private function processNewOrders()
    {
        $orders = Order::where('status', 'validation')->get();
        if ($orders->isEmpty()) {
            return;
        }

        foreach ($orders as $order) {
            try {
                // Process the order synchronously to avoid waiting for queue workers
                \App\Jobs\ProcessOrder::dispatchSync($order);
                
                // If using database queue, clean up the duplicate payload to avoid double-processing
                if (\Illuminate\Support\Facades\Schema::hasTable('jobs')) {
                    \Illuminate\Support\Facades\DB::table('jobs')
                        ->where('payload', 'like', '%ProcessOrder%')
                        ->where('payload', 'like', '%"id":' . $order->id . '%')
                        ->delete();
                }
            } catch (\Exception $e) {
                Log::error("Failed to explicitly process validation order #{$order->id}: " . $e->getMessage());
            }
        }
    }
}
