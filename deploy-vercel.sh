#!/bin/bash
# Deployment preparation script for Vercel

echo "Preparing Laravel application for Vercel deployment..."

# Ensure we're in the right directory
cd "$(dirname "$0")"

# Install production dependencies
echo "Installing production dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not present
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configurations
echo "Clearing and caching configurations..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Prepare for production
php artisan config:cache
php artisan route:cache

# Build frontend assets
echo "Building frontend assets..."
npm run build

echo "Preparation complete! Ready for Vercel deployment."
echo ""
echo "To deploy, run:"
echo "  vercel --prod"
echo ""
echo "Make sure to set the required environment variables in your Vercel dashboard."