<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/admin/bundles',
        'POST',
        [
            'network' => 'MTN',
            'is_active' => 1,
            'bundles' => [
                [
                    'name' => '1GB Bulk Test',
                    'data_amount' => '1 GB',
                    'cost_price' => 5,
                    'price' => 6
                ],
                [
                    'name' => '2GB Bulk Test',
                    'data_amount' => '2 GB',
                    'cost_price' => 10,
                    'price' => 12
                ]
            ]
        ]
    )
);

echo "Status: " . $response->getStatusCode() . "\n";
echo "1GB Bulk Test Count: " . \App\Models\Bundle::where('name', '1GB BULK TEST')->count() . "\n";
echo "2GB Bulk Test Count: " . \App\Models\Bundle::where('name', '2GB BULK TEST')->count() . "\n";

\App\Models\Bundle::where('name', '1GB BULK TEST')->delete();
\App\Models\Bundle::where('name', '2GB BULK TEST')->delete();
