<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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

        // Transition from validation/pending to processing
        $this->order->update(['status' => 'processing']);

        try {
            $result = $apiService->processOrder($this->order);

            if ($result['success']) {
                $this->order->complete($result);
                Log::info("Order ID: " . $this->order->id . " delivered successfully via API.");
            } else {
                $this->order->update([
                    'status' => 'failed',
                    'response_data' => $result
                ]);
                Log::warning("Order ID: " . $this->order->id . " failed API processing. Message: " . ($result['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error("Order ID: " . $this->order->id . " exception: " . $e->getMessage());
            $this->order->update([
                'status' => 'failed',
                'response_data' => ['error' => $e->getMessage()]
            ]);
        }
    }
}
