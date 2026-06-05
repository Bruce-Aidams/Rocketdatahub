<?php

function command_exists($cmd) {
    $which = DIRECTORY_SEPARATOR === '\\' ? 'where' : 'which';
    $process = @proc_open(
        "$which $cmd",
        [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ],
        $pipes
    );
    if (is_resource($process)) {
        fclose($pipes[1]);
        fclose($pipes[2]);
        return proc_close($process) === 0;
    }
    return false;
}

if (command_exists('npx')) {
    // If npx is available, run the original concurrently command
    $cmd = 'npx concurrently -c "#93c5fd,#c4b5fd,#fdba74" "php artisan serve" "php artisan queue:listen --tries=1 --timeout=0" "npm run dev" --names=server,queue,vite --kill-others';
    passthru($cmd);
    exit;
}

echo "npx not found. Running dev environment in PHP fallback mode...\n";
echo "Starting: php artisan serve\n";
echo "Starting: php artisan queue:listen --tries=1 --timeout=0\n";

$descriptorspec = [
    0 => ['pipe', 'r'],   // stdin
    1 => ['pipe', 'w'],   // stdout
    2 => ['pipe', 'w'],   // stderr
];

// Start server
$serverProcess = proc_open('php artisan serve', $descriptorspec, $serverPipes);
// Start queue
$queueProcess = proc_open('php artisan queue:listen --tries=1 --timeout=0', $descriptorspec, $queuePipes);

if (!is_resource($serverProcess) || !is_resource($queueProcess)) {
    echo "Failed to start processes.\n";
    exit(1);
}

// Make stdout/stderr non-blocking
stream_set_blocking($serverPipes[1], 0);
stream_set_blocking($serverPipes[2], 0);
stream_set_blocking($queuePipes[1], 0);
stream_set_blocking($queuePipes[2], 0);

// Register shutdown function to kill processes on exit
register_shutdown_function(function() use ($serverProcess, $queueProcess, $serverPipes, $queuePipes) {
    echo "\nStopping dev servers...\n";
    @fclose($serverPipes[0]);
    @fclose($serverPipes[1]);
    @fclose($serverPipes[2]);
    @fclose($queuePipes[0]);
    @fclose($queuePipes[1]);
    @fclose($queuePipes[2]);
    
    $serverStatus = proc_get_status($serverProcess);
    if ($serverStatus && $serverStatus['running']) {
        if (DIRECTORY_SEPARATOR === '\\') {
            exec("taskkill /F /T /PID " . $serverStatus['pid']);
        } else {
            proc_terminate($serverProcess);
        }
    }
    
    $queueStatus = proc_get_status($queueProcess);
    if ($queueStatus && $queueStatus['running']) {
        if (DIRECTORY_SEPARATOR === '\\') {
            exec("taskkill /F /T /PID " . $queueStatus['pid']);
        } else {
            proc_terminate($queueProcess);
        }
    }
    
    proc_close($serverProcess);
    proc_close($queueProcess);
});

// Loop to read output
while (true) {
    $serverStatus = proc_get_status($serverProcess);
    $queueStatus = proc_get_status($queueProcess);
    
    if (!$serverStatus['running'] || !$queueStatus['running']) {
        echo "One of the processes stopped running.\n";
        break;
    }
    
    // Read server stdout
    while (($line = fgets($serverPipes[1])) !== false) {
        echo "[server] " . $line;
    }
    // Read server stderr
    while (($line = fgets($serverPipes[2])) !== false) {
        echo "[server-err] " . $line;
    }
    
    // Read queue stdout
    while (($line = fgets($queuePipes[1])) !== false) {
        echo "[queue] " . $line;
    }
    // Read queue stderr
    while (($line = fgets($queuePipes[2])) !== false) {
        echo "[queue-err] " . $line;
    }
    
    usleep(100000); // Sleep for 100ms
}
