<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/', 'GET');

// Handle once to warm up cache
$app->handle($request);

DB::enableQueryLog();
DB::flushQueryLog();

echo "--- HANDLING REQUEST (CACHED) ---\n";
$start = microtime(true);
$response = $app->handle($request);
$end = microtime(true);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Time: " . ($end - $start) . "s\n\n";

echo "--- QUERIES (Should be 1 for bundles, 0 for settings) ---\n";
$queries = DB::getQueryLog();
foreach ($queries as $q) {
    echo sprintf("%.4fs: %s [%s]\n", $q['time'] / 1000, $q['query'], implode(', ', $q['bindings']));
}
