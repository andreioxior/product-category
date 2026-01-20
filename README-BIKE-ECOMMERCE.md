# Bike Ecommerce Site

A Laravel-based ecommerce platform for bike products with full search capabilities.

## Features

- ✅ 50 product categories (Mountain Bikes, Road Bikes, Components, etc.)
- ✅ 500+ products with realistic bike data
- ✅ Full-text search with Typesense
- ✅ Category-based filtering
- ✅ Manufacturer information
- ✅ Stock management
- ✅ Price ranges ($50 - $5,000)
- ✅ Docker-based development environment

## Database Structure

### Categories
- `name` - Category name
- `description` - Category description
- `slug` - URL-friendly identifier
- `is_active` - Active status

### Products
- `category_id` - Foreign key to categories
- `name` - Product name
- `description` - Product description
- `type` - Product type (Mountain, Road, Component, etc.)
- `manufacturer` - Brand name (Trek, Specialized, Shimano, etc.)
- `price` - Product price
- `sku` - Stock keeping unit
- `stock_quantity` - Available stock
- `is_active` - Active status

## Quick Start

1. **Start Docker containers:**
```bash
docker-compose up -d --build
```

2. **Setup database with sample data:**
```bash
bash setup-bike-ecommerce.sh
```

Or manually:
```bash
docker-compose exec app php artisan migrate:fresh --force
docker-compose exec app php artisan db:seed --class=BikeProductSeeder
```

3. **Access your application:**
- Application: http://localhost:8000
- Database: localhost:5432
- Typesense Dashboard: http://localhost:8108

## Product Examples

### Bikes
- 2025 Trek Pro Mountain Bike - $2,500
- 2024 Specialized Elite Road Bike - $3,200
- 2023 Giant Electric Commuter - $1,800

### Components
- Shimano Dura-Ace Derailleur - $450
- SRAM XX1 Cassette - $380
- Fox Factory 36 Fork - $1,100

### Accessories
- Specialized Ambush Helmet - $250
- Troy Lee Designs Glove - $75
- Giro Empire Shoe - $280

## Search Functionality

Search is powered by Typesense and Laravel Scout:

```php
// Search products
$results = Product::search('mountain bike')->get();

// Search with pagination
$results = Product::search('shimano')->paginate(10);

// Filter by category
$results = Product::search('tire')->where('category', 'Tires')->get();
```

## Index Products for Search

```bash
# Import all products to Typesense
docker-compose exec app php artisan scout:import "App\Models\Product"

# Flush search index
docker-compose exec app php artisan scout:flush "App\Models\Product"
```

## Manufacturers

The database includes products from 50+ manufacturers:
- Premium: Trek, Specialized, Giant, Cannondale, Santa Cruz
- Performance: Yeti, Pivot, IBIS, Transition, Norco
- Components: Shimano, SRAM, Campagnolo, Fox, RockShox
- Accessories: Giro, Specialized, Troy Lee Designs, Mavic
- And many more!

## Tech Stack

- **Backend:** Laravel 12, PHP 8.4
- **Frontend:** Livewire 3, Flux UI, Tailwind CSS v4
- **Database:** PostgreSQL 16
- **Search:** Typesense 26
- **Container:** Docker Compose
- **Queue:** Redis
- **Testing:** PHPUnit

## Development

```bash
# Run tests
docker-compose exec app php artisan test

# Format code
docker-compose exec app composer lint

# Watch files (dev mode)
docker-compose logs -f app
```

## License

MIT
