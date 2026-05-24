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
            ?? $request->input('data.order_id')
            ?? $request->input('request_id')
            ?? $request->input('data.request_id')
            ?? $request->input('id')
            ?? $request->input('data.id')
            ?? $request->input('external_reference')
            ?? $request->input('data.external_reference')
            ?? $request->input('external_id')
            ?? $request->input('data.external_id')
            ?? $request->input('ext_reference')
            ?? $request->input('data.ext_reference');

        if (!$reference) {
            Log::warning('Webhook ignored: No reference found in payload.');
            return response()->json(['message' => 'Reference not found'], 400);
        }

        // Search options:
        // 1. Exact match on reference column
        // 2. Exact match on database id column
        // 3. Parse database id from request_id format (e.g. "12-1715201")
        // 4. Exact match on stored external order_id/reference in response_data JSON
        $parsedId = is_numeric($reference) ? (int)$reference : (int)explode('-', $reference)[0];

        $order = Order::where('reference', $reference)
            ->orWhere('id', $parsedId)
            ->orWhereJsonContains('response_data->raw_response->order_id', $reference)
            ->orWhereJsonContains('response_data->raw_response->data->order_id', $reference)
            ->orWhereJsonContains('response_data->raw_response->data->id', $reference)
            ->orWhereJsonContains('response_data->raw_response->external_reference', $reference)
            ->orWhereJsonContains('response_data->raw_response->data->external_reference', $reference)
            ->orWhereJsonContains('response_data->raw_response->external_id', $reference)
            ->orWhereJsonContains('response_data->raw_response->data->external_id', $reference)
            ->first();

        if (!$order) {
            Log::warning("Webhook ignored: Order with identifier '$reference' not found.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 2.5 Security Validation
        $apiService = app(\App\Services\ApiService::class);
        $provider = $apiService->getProviderForOrder($order);
        if ($provider) {
            // Check IP Whitelist
            if (!empty($provider->webhook_allowed_ips)) {
                $allowedIps = array_map('trim', explode(',', $provider->webhook_allowed_ips));
                $clientIp = $request->ip();
                if (!in_array($clientIp, $allowedIps) && !in_array('*', $allowedIps)) {
                    Log::warning("Webhook blocked: IP {$clientIp} is not in whitelisted IPs for provider {$provider->name}");
                    return response()->json(['message' => 'Forbidden: IP address not allowed'], 403);
                }
            }

            // Check Secret Token / HMAC Signature Verification
            if (!empty($provider->webhook_secret)) {
                $secret = $provider->webhook_secret;
                $passedSecret = $request->header('X-Webhook-Secret') ?? $request->query('secret');
                
                $authenticated = false;
                
                // Direct match check
                if ($passedSecret && hash_equals($secret, $passedSecret)) {
                    $authenticated = true;
                } else {
                    // Try HMAC signature verification
                    $payload = $request->getContent();
                    $signatureHeaders = [
                        'X-Signature',
                        'X-Hub-Signature',
                        'X-Paystack-Signature',
                        'X-Webhook-Signature',
                        'Signature'
                    ];
                    
                    foreach ($signatureHeaders as $headerName) {
                        $signature = $request->header($headerName);
                        if ($signature) {
                            $cleanSignature = preg_replace('/^(sha256|sha512)=/i', '', $signature);
                            if (hash_equals(hash_hmac('sha512', $payload, $secret), $cleanSignature) ||
                                hash_equals(hash_hmac('sha256', $payload, $secret), $cleanSignature)) {
                                $authenticated = true;
                                break;
                            }
                        }
                    }
                }
                
                if (!$authenticated) {
                    Log::warning("Webhook blocked: Invalid secret/signature for provider {$provider->name}");
                    return response()->json(['message' => 'Unauthorized: Invalid signature or secret key'], 401);
                }
            }
        }

        // 3. Determine and Verify Status (Centralized external status check)
        $externalData = $apiService->fetchExternalStatus($order);

        $payloadStatus = $request->input('status') ?? $request->input('data.status');
        $status = null;
        $responseData = $request->all();

        if ($externalData) {
            $status = $externalData['status'];
            $responseData = $externalData['response'];
            Log::info("Webhook for Order #{$order->id} verified status: {$status}");
        } else {
            $status = $payloadStatus;
            Log::info("Webhook for Order #{$order->id} fell back to payload status: {$status}");
        }

        // Normalize status to lowercase string, handling booleans safely
        if (is_bool($status)) {
            $status = $status ? 'success' : 'failed';
        }
        $status = strtolower(is_string($status) ? $status : 'pending');

        if ($this->isSuccessStatus($status)) {
            if ($order->status !== 'delivered') {
                $order->complete(array_merge($order->response_data ?? [], ['webhook' => $responseData]));
                Log::info("Order #{$order->id} completed via webhook.");
            }
        } elseif ($this->isFailedStatus($status)) {
            if ($order->status !== 'failed') {
                $order->update([
                    'status' => 'failed',
                    // Append webhook data to existing response data
                    'response_data' => array_merge($order->response_data ?? [], ['webhook' => $responseData])
                ]);
                Log::info("Order #{$order->id} marked as failed via webhook.");
            }
        } elseif ($this->isPendingStatus($status)) {
            if ($order->status !== 'validation') {
                $order->update([
                    'status' => 'validation',
                    'response_data' => array_merge($order->response_data ?? [], ['webhook' => $responseData])
                ]);
                Log::info("Order #{$order->id} marked as validating (pending) via webhook.");
            }
        } elseif ($this->isProcessingStatus($status)) {
            if ($order->status !== 'processing') {
                $order->update([
                    'status' => 'processing',
                    'response_data' => array_merge($order->response_data ?? [], ['webhook' => $responseData])
                ]);
                Log::info("Order #{$order->id} marked as processing via webhook.");
            }
        }

        return response()->json(['message' => 'Webhook processed']);
    }

    private function getStatusWeight($status)
    {
        if ($this->isSuccessStatus($status) || $this->isFailedStatus($status)) {
            return 3;
        }
        if ($this->isProcessingStatus($status)) {
            return 2;
        }
        if ($this->isPendingStatus($status)) {
            return 1;
        }
        return 0;
    }

    private function isSuccessStatus($status)
    {
        if (is_bool($status)) {
            $status = $status ? 'success' : 'failed';
        }
        if (is_string($status)) {
            $status = strtolower($status);
            return in_array($status, ['success', 'successful', 'completed', 'delivered']);
        }
        return false;
    }

    private function isFailedStatus($status)
    {
        if (is_bool($status)) {
            $status = $status ? 'success' : 'failed';
        }
        if (is_string($status)) {
            $status = strtolower($status);
            return in_array($status, ['failed', 'error', 'reversed', 'refunded']);
        }
        return false;
    }

    private function isPendingStatus($status)
    {
        if (is_string($status)) {
            $status = strtolower($status);
            return in_array($status, ['pending', 'validation', 'validating']);
        }
        return false;
    }

    private function isProcessingStatus($status)
    {
        if (is_string($status)) {
            $status = strtolower($status);
            return in_array($status, ['processing', 'queued']);
        }
        return false;
    }
}
