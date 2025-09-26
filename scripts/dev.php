<?php

/**
 * Cross-platform Laravel development server script
 * Works on Windows, macOS, and Linux
 */

$os = strtolower(PHP_OS_FAMILY);

echo "Starting Laravel development environment...\n";
echo "Detected OS: " . PHP_OS_FAMILY . "\n";

// Check if concurrently is installed
$nullRedirect = ($os === 'windows') ? '2>nul' : '2>/dev/null';
exec("npx concurrently --version $nullRedirect", $output, $returnCode);
if ($returnCode !== 0) {
    echo "Error: concurrently is not installed. Please run: npm install\n";
    exit(1);
}

echo "Starting services: Server, Queue Worker, Log Monitor, Vite Dev Server\n";

if ($os === 'windows') {
    echo "Note: For real-time logs on Windows, run 'php artisan pail' in a separate terminal.\n";
}

echo "Press Ctrl+C to stop all services.\n\n";

// Create temporary batch files for Windows or use direct commands for Unix
if ($os === 'windows') {
    // For Windows, create a simpler approach using the existing dev.bat
    if (file_exists('dev.bat')) {
        echo "Using existing dev.bat file...\n";
        $exitCode = 0;
        passthru('dev.bat', $exitCode);
    } else {
        // Fallback: create commands array and run concurrently
        $commands = [
            'php artisan serve',
            'php artisan queue:listen --tries=1', 
            'timeout /t 3600 /nobreak',  // Simple Windows timer for logs placeholder
            'npm run dev'
        ];
        
        $concurrentlyCmd = 'npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" ' .
            '"' . implode('" "', $commands) . '"' .
            ' --names=server,queue,logs,vite --kill-others';
            
        $exitCode = 0;
        passthru($concurrentlyCmd, $exitCode);
    }
} else {
    // Unix/Linux/macOS approach
    $commands = [
        'php artisan serve',
        'php artisan queue:listen --tries=1',
        'php artisan pail --timeout=0',
        'npm run dev'
    ];
    
    $concurrentlyCmd = 'npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" ' .
        '"' . implode('" "', $commands) . '"' .
        ' --names=server,queue,logs,vite --kill-others';
        
    $exitCode = 0;
    passthru($concurrentlyCmd, $exitCode);
}

// Cleanup message
if ($exitCode !== 0) {
    echo "\nDevelopment server stopped with exit code: $exitCode\n";
} else {
    echo "\nDevelopment server stopped gracefully.\n";
}

exit($exitCode);