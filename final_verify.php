<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/', 'GET');

// First handle (cold)
$start = microtime(true);
$response = $app->handle($request);
echo "First handle (Coldish): " . (microtime(true) - $start) . "s\n";

// Second handle (warm)
$start = microtime(true);
$response = $app->handle($request);
echo "Second handle (Warm): " . (microtime(true) - $start) . "s\n";

echo "Status: " . $response->getStatusCode() . "\n";
