<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$kernel->handle($request);

$transaction = \App\Models\Transaction::first();
if (!$transaction) { echo "No transaction found to test PDF."; exit;}

try {
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.transaction', compact('transaction'))
                ->setPaper([0, 0, 450, 700], 'portrait');
    $output = $pdf->output();
    if(strlen($output) > 1000) {
        echo "PDF rendered successfully (Size: " . strlen($output) . " bytes)\n";
    } else {
        echo "PDF rendering failed or output too small.\n";
    }
} catch (\Exception $e) {
    echo "DomPDF Error: " . $e->getMessage() . "\n";
}
