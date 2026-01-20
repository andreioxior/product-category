# AGENTS.md

This file contains guidelines and commands for agentic coding agents working in this Laravel Livewire product category management application.

## Project Overview

This is a Laravel 12 application using Livewire 3 with Flux UI components for building a product category management system. The project follows Laravel conventions and uses modern PHP practices.

## Build, Lint, and Test Commands

### Development Commands
```bash
# Start development server (includes queue and vite)
composer run dev

# Start individual services
php artisan serve                    # Laravel dev server
php artisan queue:listen --tries=1 # Queue worker
npm run dev                         # Vite dev server
```

### Build Commands
```bash
# Build production assets
npm run build

# Setup fresh installation
composer run setup
```

### Lint Commands
```bash
# Run Laravel Pint (code formatter)
composer lint
composer run lint

# Run Pint with test mode (checks if fixes are needed)
composer run test:lint
```

### Test Commands
```bash
# Run full test suite (includes lint check)
composer test
composer run test

# Run PHPUnit tests directly
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/Feature/Auth/AuthenticationTest.php

# Run specific test method
./vendor/bin/phpunit --filter test_users_can_authenticate_using_the_login_screen

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage
```

## Code Style Guidelines

### PHP Code Style

#### Formatting
- Use **Laravel Pint** for code formatting (runs automatically in CI)
- Follow PSR-12 coding standard
- Use 4 spaces for indentation (no tabs)
- Files must end with a newline character

#### Imports and Namespaces
- Alphabetize `use` statements
- Group imports: external libraries first, then internal app classes
- Use fully qualified class names only when necessary
- Prefer importing over using fully qualified names in code

```php
<?php

namespace App\Livewire\Settings;

use Exception;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
```

#### Type Declarations
- Always declare return types for methods (`: void`, `: string`, `: array`, etc.)
- Use strict typing for parameters when possible
- Use PHP 8+ union types and enum types where appropriate
- Use `#[Validate]` attributes for Livewire validation rules

#### Naming Conventions
- **Classes**: PascalCase (e.g., `TwoFactorAuthentication`)
- **Methods**: camelCase (e.g., `enableTwoFactorAuthentication`)
- **Properties**: camelCase (e.g., `$twoFactorEnabled`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_ATTEMPTS`)
- **Variables**: camelCase, descriptive names

#### Documentation
- Use PHPDoc blocks for all public methods and properties
- Include `@param`, `@return`, and `@throws` annotations
- Use inline comments only for complex business logic

```php
/**
 * Enable two-factor authentication for the user.
 *
 * @throws Exception When setup data cannot be loaded
 */
public function enable(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
{
    // Implementation
}
```

### Livewire Component Guidelines

#### Component Structure
```php
<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;

class ComponentName extends Component
{
    #[Locked]
    public bool $readOnlyProperty;

    #[Validate('required|string|max:255')]
    public string $validatedProperty;

    public function mount(): void
    {
        // Mount logic
    }

    public function save(): void
    {
        $this->validate();
        // Save logic
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.settings.component-name');
    }
}
```

#### Validation
- Use `#[Validate]` attributes for declarative validation
- Include `onUpdate: false` for fields that should only validate on form submission
- Use custom validation rules when needed

### Frontend Guidelines

#### JavaScript/TypeScript
- Use ES6+ modules and syntax
- Follow the existing Vite configuration
- Use modern async/await patterns for API calls

#### CSS/Tailwind
- Use Tailwind CSS v4 with the new `@theme` syntax
- Follow the existing color scheme (zinc-based palette)
- Use CSS custom properties for theme variables
- Apply `@layer base` for base styles, `@layer components` for component styles

#### Blade Templates
- Use Flux UI components when available
- Follow semantic HTML5 structure
- Use proper accessibility attributes (ARIA labels, etc.)
- Include proper CSRF meta tags in layouts

### Database Guidelines

#### Migrations
- Use descriptive migration names
- Include proper foreign key constraints
- Use `->onDelete('cascade')` when appropriate
- Add indexes for frequently queried columns

#### Models
- Use proper fillable arrays for mass assignment
- Define casts for all non-string attributes
- Use relationships with proper return types
- Include accessors and mutators with proper type hints

### Error Handling

#### Exceptions
- Use specific exception types rather than generic `Exception`
- Create custom exception classes for domain-specific errors
- Use `abort_unless()` and `abort_if()` for authorization checks
- Log errors with appropriate context

#### Validation Errors
- Use Laravel's built-in validation error handling
- Provide clear, user-friendly error messages
- Use proper error bag naming for complex forms

### Security Guidelines

#### Authentication & Authorization
- Use Laravel Fortify for authentication features
- Implement proper two-factor authentication
- Use policy classes for authorization logic
- Validate all user inputs

#### Data Protection
- Never expose sensitive data in responses
- Use proper password hashing (Laravel's default)
- Implement rate limiting for sensitive endpoints
- Use HTTPS in production

### Testing Guidelines

#### Test Structure
- Use descriptive test method names
- Follow Arrange-Act-Assert pattern
- Use RefreshDatabase trait for database tests
- Test both success and failure scenarios

#### Test Data
- Use factories for creating test data
- Use meaningful test data values
- Clean up after tests when necessary

```php
public function test_user_can_update_profile(): void
{
    // Arrange
    $user = User::factory()->create();
    $newData = ['name' => 'John Doe'];

    // Act
    $response = $this->actingAs($user)
        ->put(route('profile.update'), $newData);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('users', $newData);
}
```

## Development Workflow

1. **Before coding**: Run `composer run test:lint` to check code style
2. **During development**: Use `composer run dev` for local development
3. **Before committing**: Run `composer test` to ensure everything passes
4. **After changes**: Verify the application works in the browser

## CI/CD Pipeline

- **Lint workflow**: Runs on push/PR to main/develop/components branches
- **Test workflow**: Runs on PHP 8.4 and 8.5 with Node.js 22
- **Quality checks**: Pint formatting, PHPUnit tests, asset building

## Package Management

### Composer
- Use `composer install` for production
- Use `composer update` carefully (check changes)
- Add Flux UI credentials when installing paid packages

### NPM
- Use `npm install` for dependencies
- Use `npm run build` for production assets
- Vite handles asset bundling and hot reloading

## Environment Configuration

- Copy `.env.example` to `.env` for local setup
- Use SQLite in-memory database for testing
- Configure proper mail settings for email features
- Set up two-factor authentication secrets in production

## Notes for Agents

- This project uses **Laravel 12** with **PHP 8.4+**
- **Livewire 3** with **Flux UI** components for the frontend
- **Tailwind CSS v4** for styling
- **PostgreSQL** database (via Docker)
- **Laravel Scout** with **Typesense** for search functionality
- **Laravel Pint** for code formatting
- **PHPUnit** for testing
- This is a **bike ecommerce** application with 50+ categories and 500+ products
- Always run tests before submitting changes
- Follow Laravel conventions and best practices
- Use proper error handling and logging
- Implement security best practices at all times

## Bike Ecommerce Database

### Models
- **Category**: name, description, slug, is_active
- **Product**: name, description, type, manufacturer, price, sku, stock_quantity, is_active

### Relationships
- Category hasMany Products
- Product belongsTo Category

### Running Migrations & Seeders
```bash
# Quick setup script
bash setup-bike-ecommerce.sh

# Or manually
docker-compose exec app php artisan migrate:fresh --force
docker-compose exec app php artisan db:seed --class=BikeProductSeeder
```

### Sample Data
- 50 categories (Mountain Bikes, Road Bikes, Components, Accessories, etc.)
- 500 products with realistic bike-related data
- Products include bikes, components, clothing, and accessories

## Search with Typesense

### Model Configuration
Add the `Searchable` trait to models that need search functionality:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    /**
     * Get the index name for the model.
     */
    public function searchableAs(): string
    {
        return 'products_index';
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category->name,
        ];
    }
}
```

### Searching
```php
// Simple search
$results = Product::search('keyword')->get();

// With pagination
$results = Product::search('keyword')->paginate(10);

// With filters
$results = Product::search('keyword')
    ->where('category', 'electronics')
    ->get();
```

### Indexing Commands
```bash
# Import all records into the search index
php artisan scout:import "App\Models\Product"

# Flush all records from the index
php artisan scout:flush "App\Models\Product"

# Re-index all records (flush then import)
php artisan scout:flush "App\Models\Product"
php artisan scout:import "App\Models\Product"
```

### Typesense Dashboard
Access the Typesense dashboard at http://localhost:8108 with the API key from your .env file.