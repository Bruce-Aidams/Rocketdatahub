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
        $provider = $this->getProviderForOrder($order);

        if (!$provider) {
            Log::error("No active API provider found for network: {$order->bundle->network}");
            throw new \Exception("No active provider found for network: {$order->bundle->network}");
        }

        Log::info("Selected Provider: {$provider->name} (ID: {$provider->id})");

        // 3. Construct the Request Body
        $requestBody = $this->buildRequestBody($provider, $order);

        // 4. Parse Headers (Important for Bearer tokens with placeholders)
        $headers = $this->parseHeaders($provider, $order);

        // 5. Send Request with Retries
        $url = $this->replacePlaceholders($provider->base_url, $provider, $order);
        $response = Http::withHeaders($headers)
                    ->timeout($provider->timeout_seconds ?? 30)
                    ->retry($provider->retry_attempts ?? 3, 100)
                    ->when(app()->environment('local'), function ($http) {
                        return $http->withoutVerifying();
                    })
            ->{$provider->request_method ?? 'POST'}($url, $requestBody);

        // 5. Handle Response
        $responseData = $response->json() ?? [];
        $statusCode = $response->status();

        Log::info("API Response for Order #{$order->id}: " . json_encode($responseData));

        $errorMessage = null;
        $isSuccess = $response->successful() && $this->isSuccess($provider, $responseData);
        if (!$isSuccess) {
            $errorMessage = $this->extractError($provider, $responseData) ?? 'Unknown error from provider';
        }

        // Log this transaction for administrative transparency
        try {
            \App\Models\ApiLog::create([
                'provider_id' => $provider->id,
                'endpoint' => $url,
                'method' => $provider->request_method ?? 'POST',
                'status_code' => $statusCode,
                'response' => json_encode($responseData),
                'error_message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write ApiLog: " . $e->getMessage());
        }

        if ($isSuccess) {
            return [
                'success' => true,
                'data' => $this->extractData($provider, $responseData),
                'provider_id' => $provider->id,
                'raw_response' => $responseData
            ];
        }

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
        if (empty($provider->request_body_template)) {
            return [];
        }

        $template = $this->replacePlaceholders($provider->request_body_template, $provider, $order);
        return json_decode($template, true);
    }

    /**
     * Parse headers and replace placeholders.
     */
    private function parseHeaders(ApiProvider $provider, Order $order)
    {
        $headers = $provider->request_headers ?? [];
        if (!is_array($headers)) return [];

        foreach ($headers as $key => $value) {
            $headers[$key] = $this->replacePlaceholders($value, $provider, $order);
        }

        return $headers;
    }

    /**
     * Centralized placeholder replacement logic.
     * Supports both {key} and {{key}} formats.
     */
    private function replacePlaceholders(string $string, ApiProvider $provider, Order $order): string
    {
        $dataAmount = $order->bundle->data_amount ?? '0';
        $numericAmount = preg_replace('/[^0-9.]/', '', $dataAmount);

        $apiKey = $provider->api_key;
        if (!empty($apiKey) && env($apiKey) !== null) {
            $apiKey = env($apiKey);
        }

        $secretKey = $provider->secret_key;
        if (!empty($secretKey) && env($secretKey) !== null) {
            $secretKey = env($secretKey);
        }

        $replacements = [
            'phone' => $order->recipient_phone,
            'amount' => $numericAmount ?: $dataAmount,
            'capacity' => $numericAmount ?: $dataAmount,
            'network' => $order->bundle->network,
            'request_id' => $order->id . '-' . time(),
            'callback_url' => url('/api/webhooks/incoming'),
            'api_key' => $apiKey,
            'secret_key' => $secretKey,
        ];


        foreach ($replacements as $key => $value) {
            // Replace {{key}}
            $string = str_replace('{{' . $key . '}}', $value, $string);
            // Replace {key}
            $string = str_replace('{' . $key . '}', $value, $string);
        }

        return $string;
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

    /**
     * Find the best active provider for a given order.
     */
    public function getProviderForOrder(Order $order): ?ApiProvider
    {
        // 1. Try specific network match
        $provider = ApiProvider::where('network_type', $order->bundle->network)
            ->where('is_active', true)
            ->first();

        // 2. Fallback to universal provider
        if (!$provider) {
            $provider = ApiProvider::whereNull('network_type')
                ->where('is_active', true)
                ->first();
        }

        return $provider;
    }
}
