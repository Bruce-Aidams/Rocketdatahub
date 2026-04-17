<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventName;
    public $payload;

    public function __construct($eventName, $payload)
    {
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    public function handle()
    {
        $url = Setting::where('key', 'webhook_url')->first()?->value;
        $secret = Setting::where('key', 'webhook_secret')->first()?->value;

        if (!$url) {
            return;
        }

        $body = [
            'event' => $this->eventName,
            'data' => $this->payload,
            'timestamp' => now()->toIso8601String()
        ];

        $jsonPayload = json_encode($body);
        
        $signature = '';
        if ($secret) {
            $signature = hash_hmac('sha256', $jsonPayload, $secret);
        }

        try {
            $request = Http::withoutVerifying()->timeout(10)->withHeaders([
                'X-Webhook-Event' => $this->eventName,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

            if ($signature) {
                $request = $request->withHeaders([
                    'X-Signature' => $signature
                ]);
            }

            $response = $request->post($url, $body);

            if ($response->failed()) {
                Log::warning("Webhook dispatch failed for event {$this->eventName} to {$url}", [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Webhook dispatch exception for event {$this->eventName} to {$url}: " . $e->getMessage());
        }
    }
}
