#!/bin/bash

echo "🚀 Setting up bike ecommerce database..."

echo "📦 Installing dependencies..."
docker-compose exec app composer install

echo "🔄 Running migrations..."
docker-compose exec app php artisan migrate:fresh --force

echo "🌱 Seeding bike products (50 categories, 500 products)..."
docker-compose exec app php artisan db:seed --class=BikeProductSeeder

echo "✅ Setup completed!"
echo ""
echo "📊 Database Statistics:"
docker-compose exec app php artisan tinker --execute="echo 'Categories: ' . \App\Models\Category::count(); echo PHP_EOL; echo 'Products: ' . \App\Models\Product::count();"

echo ""
echo "🎉 Your bike ecommerce site is ready!"
echo "Access at: http://localhost:8000"