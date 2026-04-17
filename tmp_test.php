<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$val = \App\Models\Setting::where('key', 'webhook_events')->first()?->value;
var_dump($val);
$savedEvents = json_decode($val, true) ?? [];
var_dump($savedEvents);
