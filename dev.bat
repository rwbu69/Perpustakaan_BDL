@echo off
REM Cross-platform Laravel development script for Windows

echo Starting Laravel development environment on Windows...

REM Check if npm dependencies are installed
if not exist "node_modules" (
    echo Installing npm dependencies...
    call npm install
)

REM Check if concurrently is available
call npx concurrently --version >nul 2>&1
if errorlevel 1 (
    echo Error: concurrently is not installed. Please run: npm install
    exit /b 1
)

echo Starting services: Server, Queue Worker, Log Viewer ^(Limited^), Vite Dev Server
echo Note: Laravel Pail may have limited functionality on Windows.
echo For real-time logs, run 'php artisan pail' in a separate terminal.
echo Press Ctrl+C to stop all services.
echo.

REM Run all services with concurrently
call npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" ^
    "php artisan serve" ^
    "php artisan queue:listen --tries=1" ^
    "echo [LOGS] Running on Windows. For detailed logs, check storage/logs/laravel.log or run: php artisan log:tail" ^
    "npm run dev" ^
    --names=server,queue,logs,vite ^
    --kill-others