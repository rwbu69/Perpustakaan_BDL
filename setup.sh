#!/bin/bash

echo "Setting up Library Management System..."

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders
echo "Seeding database with sample data..."
php artisan db:seed --force

echo "Setup completed!"
echo ""
echo "You can login with:"
echo "Admin: username='admin', password='password'"
echo "User: username='takeshi', password='password'"
echo "User: username='yuki', password='password'"