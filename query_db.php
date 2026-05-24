<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use Illuminate\Support\Facades\DB;

$counts = Order::select('status', DB::raw('count(*) as count'))
    ->groupBy('status')
    ->get()
    ->toArray();

$completed = Order::where('status', 'completed')->get(['id', 'reference', 'status', 'source', 'payment_method', 'created_at'])->toArray();
echo "COMPLETED ORDERS DETAILS:\n";
print_r($completed);
