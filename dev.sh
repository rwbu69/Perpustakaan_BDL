#!/bin/bash

# Cross-platform Laravel development script for Unix-like systems (Linux/macOS)
# For Windows, use dev.bat or composer run dev

echo "Starting Laravel development environment on $(uname -s)..."

# Check if npm dependencies are installed
if [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    npm install
fi

# Check if concurrently is available
if ! command -v npx &> /dev/null || ! npx concurrently --version &> /dev/null; then
    echo "Error: concurrently is not installed. Please run: npm install"
    exit 1
fi

echo "Starting services: Server, Queue Worker, Log Viewer, Vite Dev Server"
echo "Press Ctrl+C to stop all services."
echo ""

# Run all services with concurrently
npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" \
    "php artisan serve" \
    "php artisan queue:listen --tries=1" \
    "php artisan pail --timeout=0" \
    "npm run dev" \
    --names=server,queue,logs,vite \
    --kill-others