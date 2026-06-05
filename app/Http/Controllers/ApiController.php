<?php

/**
 * ============================================================
 * API INTEGRATION COMMENTED OUT
 * All API provider management functions have been disabled.
 * To re-enable, uncomment the code below.
 * ============================================================
 */

namespace App\Http\Controllers;

// use App\Models\ApiProvider;
// use App\Models\ApiLog;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /*
    // List all providers
    public function index(Request $request)
    {
        $providers = ApiProvider::all();

        if ($request->expectsJson() || $request->is('api/*')) {
            return $providers;
        }

        $logs = ApiLog::with('provider')->latest()->paginate($request->input('per_page', 10));
        $userKeys = \App\Models\ApiKey::with('user')->latest()->get();

        return view('admin.api.index', compact('providers', 'logs', 'userKeys'));
    }

    // Create a new provider
    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active')
        ]);

        $validated = $request->validate([
            'name' => 'required|string',
            'network_type' => 'nullable|string',
            'base_url' => 'required|url',
            'request_method' => 'required|string|in:GET,POST,PUT',
            'status_endpoint' => 'nullable|string',
            'status_request_method' => 'nullable|string|in:GET,POST,PUT',
            'request_headers' => 'nullable|string',
            'request_body' => 'nullable|string',
            'request_body_template' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'is_active' => 'boolean',
            'timeout_seconds' => 'nullable|integer|min:5|max:300',
            'retry_attempts' => 'nullable|integer|min:0|max:10',
            'response_success_field' => 'nullable|string',
            'response_data_field' => 'nullable|string',
            'response_error_field' => 'nullable|string'
        ]);

        // Process JSON strings
        if (isset($validated['request_headers'])) {
            $validated['request_headers'] = json_decode($validated['request_headers'], true);
        }
        if (isset($validated['request_body'])) {
            $validated['request_body'] = json_decode($validated['request_body'], true);
        }

        $provider = ApiProvider::create($validated);

        // Sync to .env if it's a known provider
        $this->syncToEnv($provider);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'API Provider added successfully']);
        }

        return redirect()->back()->with('success', 'API Provider added successfully');
    }

    // Update a provider
    public function update(Request $request, $id)
    {
        $provider = ApiProvider::findOrFail($id);

        $request->merge([
            'is_active' => $request->has('is_active')
        ]);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'network_type' => 'nullable|string',
            'base_url' => 'sometimes|url',
            'request_method' => 'sometimes|string|in:GET,POST,PUT',
            'status_endpoint' => 'nullable|string',
            'status_request_method' => 'nullable|string|in:GET,POST,PUT',
            'request_headers' => 'nullable|string',
            'request_body' => 'nullable|string',
            'request_body_template' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'is_active' => 'boolean',
            'timeout_seconds' => 'nullable|integer|min:5|max:300',
            'retry_attempts' => 'nullable|integer|min:0|max:10',
            'response_success_field' => 'nullable|string',
            'response_data_field' => 'nullable|string',
            'response_error_field' => 'nullable|string'
        ]);

        // Process JSON strings
        if (isset($validated['request_headers'])) {
            $validated['request_headers'] = json_decode($validated['request_headers'], true);
        }
        if (isset($validated['request_body'])) {
            $validated['request_body'] = json_decode($validated['request_body'], true);
        }

        $provider->update($validated);

        // Sync to .env
        $this->syncToEnv($provider);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'API Provider updated successfully']);
        }

        return redirect()->back()->with('success', 'API Provider updated successfully');
    }

    private function syncToEnv(ApiProvider $provider)
    {
        if (!$provider->is_active)
            return;

        $name = strtoupper($provider->name);
        if ($name === 'PAYSTACK') {
            \App\Helpers\EnvHelper::set('PAYSTACK_SECRET_KEY', $provider->secret_key);
            \App\Helpers\EnvHelper::set('PAYSTACK_PUBLIC_KEY', $provider->api_key);
        }
    }

    // Delete a provider
    public function destroy(Request $request, $id)
    {
        ApiProvider::destroy($id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'API Provider deleted successfully']);
        }

        return redirect()->back()->with('success', 'API Provider deleted successfully');
    }

    // Get API Error Logs
    public function logs(Request $request)
    {
        return ApiLog::with('provider')->latest()->paginate($request->input('per_page', 10));
    }

    // Test API Provider Connection
    public function testConnection(Request $request)
    {
        $validated = $request->validate([
            'base_url' => 'required|url',
            'request_method' => 'required|string|in:GET,POST,PUT',
            'request_headers' => 'nullable|string',
            'request_body' => 'nullable|string',
            'request_body_template' => 'nullable|string',
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'timeout_seconds' => 'nullable|integer|min:5|max:300'
        ]);

        try {
            $timeout = $validated['timeout_seconds'] ?? 30;
            $headers = [];

            $apiKey = $validated['api_key'] ?? '';
            $secretKey = $validated['secret_key'] ?? '';

            // Resolve from env if it matches an env variable name
            if (!empty($apiKey) && env($apiKey) !== null) {
                $apiKey = env($apiKey);
            }
            if (!empty($secretKey) && env($secretKey) !== null) {
                $secretKey = env($secretKey);
            }

            // 1. Look for a validating order first
            $order = \App\Models\Order::where('status', 'validation')->latest()->first();

            // 2. If none, check if there's any active job in the queue table and find its corresponding order
            if (!$order && \Schema::hasTable('jobs')) {
                $job = \DB::table('jobs')->latest()->first();
                if ($job && !empty($job->payload)) {
                    try {
                        $payload = json_decode($job->payload, true);
                        $command = $payload['data']['command'] ?? null;
                        if ($command) {
                            $unserialized = unserialize($command);
                            if ($unserialized && isset($unserialized->order)) {
                                $order = $unserialized->order;
                            }
                        }
                    } catch (\Exception $e) {
                        // Safe fallback if decoding/unserializing fails
                    }
                }
            }

            // 3. Fallback to processing, pending, failed, or just the absolute latest order
            if (!$order) {
                $order = \App\Models\Order::whereIn('status', ['processing', 'pending', 'failed'])->latest()->first()
                    ?? \App\Models\Order::latest()->first();
            }

            $dataAmount = $order?->bundle?->data_amount ?? '1GB';
            $numericAmount = preg_replace('/[^0-9.]/', '', $dataAmount) ?: $dataAmount;
            $phone = $order?->recipient_phone ?? '0551518931';
            $network = $order?->bundle?->network ?? 'MTN';
            $requestId = ($order?->id ?? 'TEST') . '-' . time();

            $replacements = [
                'phone' => $phone,
                'amount' => $numericAmount,
                'capacity' => $numericAmount,
                'network' => $network,
                'request_id' => $requestId,
                'callback_url' => url('/api/webhooks/incoming'),
                'api_key' => $apiKey,
                'secret_key' => $secretKey,
            ];

            $replaceFn = function($str) use ($replacements) {
                if (empty($str)) return $str;
                foreach ($replacements as $key => $value) {
                    $str = str_replace('{{' . $key . '}}', $value, $str);
                    $str = str_replace('{' . $key . '}', $value, $str);
                }
                return $str;
            };

            // Parse headers if provided
            if (!empty($validated['request_headers'])) {
                $parsedHeaders = json_decode($validated['request_headers'], true);
                if (is_array($parsedHeaders)) {
                    foreach ($parsedHeaders as $key => $value) {
                        $value = $replaceFn($value);
                        $headers[] = "$key: $value";
                    }
                }
            }

            // Automatically add API Key if provided and not already in headers
            if (!empty($apiKey)) {
                $hasApiKeyToken = false;
                foreach ($headers as $h) {
                    if (stripos($h, 'Authorization') === 0 || stripos($h, 'X-API-KEY') === 0 || stripos($h, 'api-key') === 0) {
                        $hasApiKeyToken = true;
                        break;
                    }
                }
                if (!$hasApiKeyToken) {
                    $headers[] = "X-API-KEY: " . $apiKey;
                    $headers[] = "Authorization: Bearer " . $apiKey;
                }
            }

            // Resolve placeholders in the Base URL
            $url = $replaceFn($validated['base_url']);

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $validated['request_method']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local/dev testing

            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            // Add body for POST/PUT requests
            if (in_array($validated['request_method'], ['POST', 'PUT'])) {
                $bodyString = $validated['request_body'] ?? $validated['request_body_template'] ?? null;

                if (!empty($bodyString)) {
                    $bodyString = $replaceFn($bodyString);

                    // Try to decode to ensure valid JSON, or send as string if not
                    $body = json_decode($bodyString, true);
                    if (is_array($body)) {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                    } else {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyString);
                    }
                }
            }

            $startTime = microtime(true);
            $response = curl_exec($ch);
            $responseTime = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection failed: ' . $error,
                    'response_time' => $responseTime
                ], 500);
            }

            $isSuccess = ($httpCode >= 200 && $httpCode < 300);
            $decodedResponse = json_decode($response, true);

            return response()->json([
                'success' => $isSuccess,
                'message' => $isSuccess ? 'Connection successful' : 'Connection failed with status ' . $httpCode,
                'http_code' => $httpCode,
                'response_time' => $responseTime . 'ms',
                'response' => $decodedResponse ?? $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle API Provider Active Status
    public function toggleActive(Request $request, $id)
    {
        $provider = ApiProvider::findOrFail($id);
        $provider->is_active = !$provider->is_active;
        $provider->save();

        // Sync to .env if needed
        $this->syncToEnv($provider);

        return response()->json([
            'success' => true,
            'message' => 'API Provider ' . ($provider->is_active ? 'activated' : 'deactivated') . ' successfully',
            'is_active' => $provider->is_active
        ]);
    }
    */
}
