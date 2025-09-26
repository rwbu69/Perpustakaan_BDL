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

REM Run all services with concurrently (without logs service to avoid complexity)
echo Starting Laravel development server, queue worker, and Vite...
echo For logs, manually run: php artisan pail in a separate terminal.
echo.
call npx concurrently -c "#93c5fd,#c4b5fd,#fdba74" ^
    "php artisan serve --host=127.0.0.1 --port=8000" ^
    "php artisan queue:listen --tries=1" ^
    "npm run dev" ^
    --names=server,queue,vite