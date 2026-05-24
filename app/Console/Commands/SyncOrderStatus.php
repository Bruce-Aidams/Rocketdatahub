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
        // Poll both processing and validation (validating) orders, since validating represents pending on external vendor site
        $orders = Order::whereIn('status', ['processing', 'validation'])->get();

        if ($orders->isEmpty()) {
            return;
        }

        $apiService = app(ApiService::class);

        // Group by provider to minimize external API hits
        $providerOrders = $orders->groupBy(function ($order) use ($apiService) {
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
            $secretKey = $provider->secret_key;
            if (!empty($secretKey) && env($secretKey) !== null) {
                $secretKey = env($secretKey);
            }

            foreach ($headers as $key => $value) {
                $headers[$key] = str_replace(
                    ['{api_key}', '{{api_key}}', '{secret_key}', '{{secret_key}}'],
                    [$apiKey, $apiKey, $secretKey, $secretKey],
                    $value
                );
            }

            // Check if a dedicated status endpoint is configured
            if (!empty($provider->status_endpoint)) {
                foreach ($ordersList as $order) {
                    try {
                        $externalData = $apiService->fetchExternalStatus($order);

                        if (!$externalData) {
                            continue;
                        }

                        $vendorStatus = $externalData['status'];
                        $responseData = $externalData['response'];

                        if (in_array($vendorStatus, ['completed', 'successful', 'delivered', 'success'])) {
                            $order->complete(array_merge($order->response_data ?? [], ['sync_response' => $responseData]));
                            Log::info("Order #{$order->id} successfully synced via status endpoint and marked as delivered.");
                        } elseif (in_array($vendorStatus, ['failed', 'error', 'reversed', 'refunded'])) {
                            $order->update([
                                'status' => 'failed',
                                'response_data' => array_merge($order->response_data ?? [], ['sync_response' => $responseData])
                            ]);
                            Log::info("Order #{$order->id} successfully synced via status endpoint and marked as failed.");
                        } elseif (in_array($vendorStatus, ['pending', 'validation', 'validating'])) {
                            if ($order->status !== 'validation') {
                                $order->update([
                                    'status' => 'validation',
                                    'response_data' => array_merge($order->response_data ?? [], ['sync_response' => $responseData])
                                ]);
                                Log::info("Order #{$order->id} successfully synced via status endpoint and marked as validation.");
                            }
                        } elseif (in_array($vendorStatus, ['processing', 'queued'])) {
                            if ($order->status !== 'processing') {
                                $order->update([
                                    'status' => 'processing',
                                    'response_data' => array_merge($order->response_data ?? [], ['sync_response' => $responseData])
                                ]);
                                Log::info("Order #{$order->id} successfully synced via status endpoint and marked as processing.");
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Sync error for Order #{$order->id} on provider {$provider->name}: " . $e->getMessage());
                    }
                }
            } else {
                // Fallback to bulk-fetch logic (Legacy)
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
                            } elseif (in_array($vendorStatus, ['pending', 'validation', 'validating'])) {
                                if ($order->status !== 'validation') {
                                    $order->update([
                                        'status' => 'validation',
                                        'response_data' => array_merge($order->response_data ?? [], ['sync_response' => $matched])
                                    ]);
                                    Log::info("Order #{$order->id} successfully synced and marked as validation.");
                                }
                            } elseif (in_array($vendorStatus, ['processing', 'queued'])) {
                                if ($order->status !== 'processing') {
                                    $order->update([
                                        'status' => 'processing',
                                        'response_data' => array_merge($order->response_data ?? [], ['sync_response' => $matched])
                                    ]);
                                    Log::info("Order #{$order->id} successfully synced and marked as processing.");
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Sync error for provider {$provider->name}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Explicitly process any orders stuck in validation state
     */
    private function processNewOrders()
    {
        // Only process newly created validation orders that haven't been submitted to the API yet (response_data is null)
        $orders = Order::where('status', 'validation')->whereNull('response_data')->get();
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
