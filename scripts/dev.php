<?php

/**
 * Cross-platform Laravel development server script
 * Works on Windows, macOS, and Linux
 */

$os = strtolower(PHP_OS_FAMILY);

echo "Starting Laravel development environment...\n";
echo "Detected OS: " . PHP_OS_FAMILY . "\n";

// Check if concurrently is installed
exec('npx concurrently --version 2>/dev/null', $output, $returnCode);
if ($returnCode !== 0) {
    echo "Error: concurrently is not installed. Please run: npm install\n";
    exit(1);
}

// Define the base commands
$serverCmd = 'php artisan serve';
$queueCmd = 'php artisan queue:listen --tries=1';
$viteCmd = 'npm run dev';

// Handle logs command based on OS and pail availability
if ($os === 'windows') {
    // On Windows, pail may have issues with process control, so provide alternative
    $logsCmd = 'echo "[LOGS] Running on Windows. For detailed logs, check storage/logs/laravel.log or run: php artisan log:tail"';
    echo "Note: Laravel Pail may have limited functionality on Windows.\n";
    echo "For real-time logs, manually run: php artisan pail in a separate terminal.\n";
} else {
    // On Unix-like systems (Linux/macOS), pail should work fine
    $logsCmd = 'php artisan pail --timeout=0';
}

// Build the concurrently command with proper escaping for different shells
$concurrentlyCmd = sprintf(
    'npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" "%s" "%s" "%s" "%s" --names=server,queue,logs,vite --kill-others',
    addcslashes($serverCmd, '"'),
    addcslashes($queueCmd, '"'),
    addcslashes($logsCmd, '"'),
    addcslashes($viteCmd, '"')
);

echo "Starting services: Server, Queue Worker, Log Viewer, Vite Dev Server\n";
echo "Press Ctrl+C to stop all services.\n\n";

// Execute the command
$exitCode = 0;
passthru($concurrentlyCmd, $exitCode);

// Cleanup message
if ($exitCode !== 0) {
    echo "\nDevelopment server stopped with exit code: $exitCode\n";
} else {
    echo "\nDevelopment server stopped gracefully.\n";
}

exit($exitCode);