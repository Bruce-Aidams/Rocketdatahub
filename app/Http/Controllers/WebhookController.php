<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Log the incoming webhook
        Log::info('Webhook Received:', $request->all());

        // 2. Identify the Order
        // Try to find the order reference in common fields
        $reference = $request->input('reference')
            ?? $request->input('data.reference')
            ?? $request->input('order_id')
            ?? $request->input('request_id');

        if (!$reference) {
            Log::warning('Webhook ignored: No reference found in payload.');
            return response()->json(['message' => 'Reference not found'], 400);
        }

        $order = Order::where('reference', $reference)->first();

        if (!$order) {
            // Try searching in response_data if we stored external ID there
            // specific to MySQL/Postgres JSON path syntax, simplified here
            Log::warning("Webhook ignored: Order with reference '$reference' not found.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Determine Status
        // This logic might need to be provider-specific in a real refactor,
        // but for now we look for common success indicators.
        $status = $request->input('status') ?? $request->input('data.status');

        Log::info("Processing webhook for Order #{$order->id}. Status in payload: " . json_encode($status));

        if ($this->isSuccessStatus($status)) {
            if ($order->status !== 'delivered') {
                $order->complete($request->all());
                Log::info("Order #{$order->id} completed via webhook.");
            }
        } elseif ($this->isFailedStatus($status)) {
            if ($order->status !== 'failed') {
                $order->update([
                    'status' => 'failed',
                    // Append webhook data to existing response data
                    'response_data' => array_merge($order->response_data ?? [], ['webhook' => $request->all()])
                ]);
                Log::info("Order #{$order->id} marked as failed via webhook.");
            }
        }

        return response()->json(['message' => 'Webhook processed']);
    }

    private function isSuccessStatus($status)
    {
        if (is_string($status)) {
            $status = strtolower($status);
            return in_array($status, ['success', 'successful', 'completed', 'delivered']);
        }
        return false;
    }

    private function isFailedStatus($status)
    {
        if (is_string($status)) {
            $status = strtolower($status);
            return in_array($status, ['failed', 'error', 'reversed', 'refunded']);
        }
        return false;
    }
}
