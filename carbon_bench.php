<?php
require 'vendor/autoload.php';

$start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    \Carbon\Carbon::now();
}
$end = microtime(true);
echo "Time for 10,000 Carbon::now(): " . ($end - $start) . "s\n";

$start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    \Carbon\Carbon::parse('2024-01-01');
}
$end = microtime(true);
echo "Time for 10,000 Carbon::parse(): " . ($end - $start) . "s\n";
