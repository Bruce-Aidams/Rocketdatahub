<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ApiProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    /**
     * Process an order by sending it to the appropriate API provider.
     *
     * @param Order $order
     * @return array
     * @throws \Exception
     */
    public function processOrder(Order $order): array
    {
        Log::info("Processing Order #{$order->id} for Bundle: {$order->bundle->name} ({$order->bundle->network})");

        // 1. Find active provider for the network
        // We prioritize a provider that specifically matches the network type.
        $provider = ApiProvider::where('network_type', $order->bundle->network)
            ->where('is_active', true)
            ->first();

        // 2. Fallback to a universal provider (provider with null network_type) if no specific one found
        if (!$provider) {
            Log::info("No specific provider found for {$order->bundle->network}. Looking for universal provider.");
            $provider = ApiProvider::whereNull('network_type')
                ->where('is_active', true)
                ->first();
        }

        if (!$provider) {
            Log::error("No active API provider found for network: {$order->bundle->network}");
            throw new \Exception("No active provider found for network: {$order->bundle->network}");
        }

        Log::info("Selected Provider: {$provider->name} (ID: {$provider->id})");

        // 3. Construct the Request Body
        $requestBody = $this->buildRequestBody($provider, $order);
        $headers = $provider->request_headers ?? [];

        // 4. Send Request with Retries
        $response = Http::withHeaders($headers)
                    ->timeout($provider->timeout_seconds ?? 30)
                    ->retry($provider->retry_attempts ?? 3, 100)
            ->{$provider->request_method ?? 'POST'}($provider->base_url, $requestBody);

        // 5. Handle Response
        $responseData = $response->json();

        Log::info("API Response for Order #{$order->id}: " . json_encode($responseData));

        if ($response->successful() && $this->isSuccess($provider, $responseData)) {
            return [
                'success' => true,
                'data' => $this->extractData($provider, $responseData),
                'provider_id' => $provider->id,
                'raw_response' => $responseData
            ];
        }

        $errorMessage = $this->extractError($provider, $responseData) ?? 'Unknown error from provider';
        Log::error("Order #{$order->id} failed via {$provider->name}: $errorMessage");

        return [
            'success' => false,
            'message' => $errorMessage,
            'provider_id' => $provider->id,
            'raw_response' => $responseData
        ];
    }

    /**
     * Build the request body by replacing placeholders in the template.
     */
    private function buildRequestBody(ApiProvider $provider, Order $order)
    {
        // If no template, return empty array or default structure (legacy support)
        if (empty($provider->request_body_template)) {
            // Fallback for legacy simple providers if needed, or just return empty
            return [];
        }

        $template = $provider->request_body_template;

        // Define Placeholders
        $replacements = [
            '{{phone}}' => $order->recipient_phone,
            '{{amount}}' => $order->bundle->data_amount, // e.g., "1GB" or raw value depending on provider needs
            '{{network}}' => $order->bundle->network,
            '{{request_id}}' => $order->id . '-' . time(), // Unique ID
            '{{callback_url}}' => url('/api/webhooks/incoming'),
            // Add other necessary placeholders here
        ];

        // Perform Replacement
        foreach ($replacements as $key => $value) {
            $template = str_replace($key, $value, $template);
        }

        // Decode back to array for Http client
        return json_decode($template, true);
    }

    /**
     * Check if the response indicates success based on provider config.
     */
    private function isSuccess(ApiProvider $provider, array $response)
    {
        $successField = $provider->response_success_field ?? 'success';

        // Support dot notation for nested fields
        $value = data_get($response, $successField);

        // Flexible success check: boolean true, string 'true', 'success', '00' (some gateways)
        return $value === true || $value === 'true' || $value === 'success' || $value === '00';
    }

    /**
     * Extract relevant data from response.
     */
    private function extractData(ApiProvider $provider, array $response)
    {
        $dataField = $provider->response_data_field ?? 'data';
        return data_get($response, $dataField, []);
    }

    /**
     * Extract error message from response.
     */
    private function extractError(ApiProvider $provider, array $response)
    {
        $errorField = $provider->response_error_field ?? 'message';
        return data_get($response, $errorField, 'API Request Failed');
    }
}
