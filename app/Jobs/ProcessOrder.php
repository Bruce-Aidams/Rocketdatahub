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
     * Execute the job.
     */
    public function handle(ApiService $apiService): void
    {
        // Skip if order is already delivered or failed to prevent state overwrite
        if (in_array($this->order->status, ['delivered', 'failed'])) {
            Log::info("Order ID: " . $this->order->id . " is already " . $this->order->status . ". Skipping job.");
            return;
        }

        Log::info("Processing Order ID: " . $this->order->id . " (Current Status: " . $this->order->status . ")");

        try {
            // Check for provider availability first
            $provider = $apiService->getProviderForOrder($this->order);
            
            if (!$provider) {
                Log::info("No active API provider found for Order ID: " . $this->order->id . ". This may be a manual order. Keeping status as: " . $this->order->status);
                return;
            }

            // Transition from validation to processing only when provider exists
            $this->order->update(['status' => 'processing']);

            $result = $apiService->processOrder($this->order);

            if ($result['success']) {
                $this->order->complete($result);
                Log::info("Order ID: " . $this->order->id . " delivered successfully via API.");
            } else {
                $errorMessage = $result['message'] ?? 'Unknown API error';
                $this->order->update([
                    'status' => 'failed',
                    'response_data' => $result
                ]);
                
                $this->notifyAdmins("Order #{$this->order->reference} Failed", "Customer: {$this->order->recipient_phone}. Error: {$errorMessage}");
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

