<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

echo "--- 1. Cold Start Breakdown ---\n";
$start = microtime(true);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
echo "Kernel make: " . (microtime(true) - $start) . "s\n";

$k_start = microtime(true);
$request = Request::create('/', 'GET');
$response = $app->handle($request);
echo "First handle time: " . (microtime(true) - $k_start) . "s\n";

$db_start = microtime(true);
DB::select('SELECT 1');
echo "DB re-connect/first query: " . (microtime(true) - $db_start) . "s\n";

echo "\n--- 2. SQLite PRAGMAs ---\n";
$res = DB::select('PRAGMA journal_mode;');
echo "Journal Mode: " . print_r($res[0], true) . "\n";
$res = DB::select('PRAGMA busy_timeout;');
echo "Busy Timeout: " . print_r($res[0], true) . "\n";
$res = DB::select('PRAGMA synchronous;');
echo "Synchronous: " . print_r($res[0], true) . "\n";

echo "\n--- 3. OPcache Status ---\n";
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false); // false for less detail
    echo "OPcache Enabled: " . ($status['opcache_enabled'] ? 'Yes' : 'No') . "\n";
} else {
    echo "OPcache Extension NOT found.\n";
}

echo "\nTotal Process Time: " . (microtime(true) - $start) . "s\n";
