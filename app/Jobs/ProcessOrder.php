<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\AdminNotification;
use App\Services\ApiService;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [new \Illuminate\Queue\Middleware\RateLimited('vendor-api')];
    }

    /**
     * Execute the job.
     */
    public function handle(ApiService $apiService): void
    {
        // Skip if order is already delivered or failed to prevent state overwrite
        if (in_array($this->order->status, ['delivered', 'failed'])) {
            Log::info("Order ID: " . $this->order->id . " is already " . $this->order->status . ". Skipping job.");
            return;
        }

        // If the order has already been submitted to the provider (response_data is present) and it is in 'validation' state, skip it to prevent duplicate submission.
        if ($this->order->status === 'validation' && $this->order->response_data !== null) {
            Log::info("Order ID: " . $this->order->id . " is already in validation/pending state with the provider. Skipping duplicate submit.");
            return;
        }

        Log::info("Processing Order ID: " . $this->order->id . " (Current Status: " . $this->order->status . ")");

        try {
            // Get all active providers eligible for this order
            $providers = $apiService->getAllActiveProvidersForOrder($this->order);
            
            if ($providers->isEmpty()) {
                Log::info("No active API provider found for Order ID: " . $this->order->id . ". This may be a manual order. Keeping status as: " . $this->order->status);
                return;
            }

            // Transition from validation to processing
            $this->order->update(['status' => 'processing']);

            $result = null;
            $success = false;
            $failedAttempts = [];

            foreach ($providers as $provider) {
                Log::info("Attempting order placement on Order #{$this->order->id} with Provider: {$provider->name} (ID: {$provider->id})");
                
                try {
                    $result = $apiService->processOrder($this->order, $provider);
                    
                    if ($result && isset($result['success']) && $result['success']) {
                        $success = true;
                        break;
                    }
                    
                    $errorMessage = $result['message'] ?? 'API failed response';
                    Log::warning("Provider {$provider->name} (ID: {$provider->id}) failed to process Order #{$this->order->id}: {$errorMessage}");
                    $failedAttempts[] = [
                        'provider_id' => $provider->id,
                        'provider_name' => $provider->name,
                        'error' => $errorMessage,
                        'timestamp' => now()->toDateTimeString()
                    ];
                } catch (\Exception $e) {
                    Log::error("Provider {$provider->name} (ID: {$provider->id}) threw exception for Order #{$this->order->id}: " . $e->getMessage());
                    $failedAttempts[] = [
                        'provider_id' => $provider->id,
                        'provider_name' => $provider->name,
                        'error' => $e->getMessage(),
                        'timestamp' => now()->toDateTimeString()
                    ];
                }
            }

            if ($success && $result) {
                $rawResponse = $result['raw_response'] ?? [];
                
                $vendorStatus = $rawResponse['status'] 
                    ?? $rawResponse['data']['status'] 
                    ?? $rawResponse['state'] 
                    ?? $rawResponse['data']['state'] 
                    ?? 'completed';
                
                if (is_bool($vendorStatus)) {
                    $vendorStatus = $vendorStatus ? 'completed' : 'failed';
                }
                $vendorStatus = strtolower(is_string($vendorStatus) ? $vendorStatus : 'completed');

                // Merge failed attempts history into result response data if there were fallbacks
                if (!empty($failedAttempts)) {
                    $result['fallback_attempts'] = $failedAttempts;
                }

                if (in_array($vendorStatus, ['pending', 'validation', 'validating'])) {
                    $this->order->update([
                        'status' => 'validation',
                        'response_data' => $result
                    ]);
                    Log::info("Order ID: " . $this->order->id . " is pending (validating) on vendor site. Kept in validation state for sync worker.");
                } elseif (in_array($vendorStatus, ['processing', 'queued'])) {
                    $this->order->update([
                        'status' => 'processing',
                        'response_data' => $result
                    ]);
                    Log::info("Order ID: " . $this->order->id . " is processing on vendor site.");
                } else {
                    $this->order->complete($result);
                    Log::info("Order ID: " . $this->order->id . " delivered successfully via API.");
                }
            } else {
                // If we ran out of providers, fail the order entirely
                $errorMessage = 'All active providers failed. Attempts: ' . json_encode($failedAttempts);
                
                $this->order->update([
                    'status' => 'failed',
                    'response_data' => [
                        'success' => false,
                        'message' => $errorMessage,
                        'failed_attempts' => $failedAttempts
                    ]
                ]);
                
                $this->notifyAdmins("Order #{$this->order->reference} Failed", "Customer: {$this->order->recipient_phone}. Error: All active providers failed.");
                Log::warning("Order ID: " . $this->order->id . " failed API processing. Message: " . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error("Order ID: " . $this->order->id . " exception: " . $e->getMessage());
            $this->order->update([
                'status' => 'failed',
                'response_data' => ['error' => $e->getMessage()]
            ]);
            $this->notifyAdmins("Order #{$this->order->reference} Exception", $e->getMessage());
        }
    }

    /**
     * Notify all administrators
     */
    private function notifyAdmins(string $title, string $message): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AdminNotification::create([
                'user_id' => $admin->id,
                'title' => $title,
                'message' => $message,
                'type' => 'error',
                'is_read' => false
            ]);
        }
    }
}

