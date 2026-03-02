<?php

use App\Models\Bundle;

Route::get('/', function () {
    $bundles = Bundle::all(); // Or maybe filtering by verified/active?
    return view('home', compact('bundles'));
});

Route::get('/benchmark', function () {
    $start = microtime(true);
    $data = [
        'php_version' => PHP_VERSION,
        'memory_limit' => ini_get('memory_limit'),
        'db_check' => Bundle::first() ? 'ok' : 'empty',
        'carbon_check' => \Carbon\Carbon::now()->toDateTimeString(),
    ];
    $end = microtime(true);
    return response()->json([
        'status' => 'success',
        'time_taken' => ($end - $start) . ' seconds',
        'data' => $data
    ]);
});

// Storefront Payment Routes
Route::post('/store/purchase', [\App\Http\Controllers\StorefrontPaymentController::class, 'initialize'])->name('store.purchase');
Route::get('/store/payment/callback', [\App\Http\Controllers\StorefrontPaymentController::class, 'callback'])->name('store.callback');

Route::get('/store/{referral_code}/{network}', [\App\Http\Controllers\StorefrontController::class, 'buy'])->name('store.buy');
Route::get('/store/{referral_code}', [\App\Http\Controllers\StorefrontController::class, 'show'])->name('store.show');

Route::post('/webhooks/paystack', [\App\Http\Controllers\PaystackWebhookController::class, 'handle'])->name('webhooks.paystack');

require __DIR__ . '/auth.php';
