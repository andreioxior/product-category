<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'category_id',
        'bike_id',
        'name',
        'description',
        'image',
        'image_local_path',
        'image_cdn_url',
        'image_metadata',
        'image_hosted_locally',
        'image_synced_at',
        'type',
        'price',
        'sku',
        'stock_quantity',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::created(function (Product $product) {
            $product->clearProductCache();
            $product->clearCategoryCache();
            $product->searchable();
        });

        static::updated(function (Product $product) {
            $product->clearProductCache();
            $product->clearCategoryCache();
            $product->searchable();
        });

        static::deleted(function (Product $product) {
            $product->clearProductCache();
            $product->clearCategoryCache();
            $product->unsearchable();
        });
    }

    public function clearProductCache(): void
    {
        Cache::store('products')->clear();

        Cache::forget('total_products_count');

        Cache::forget('bike_manufacturers');

        if ($this->bike) {
            Cache::forget("bike_models_{$this->bike->manufacturer}");
            Cache::forget("bike_years_{$this->bike->manufacturer}_{$this->bike->model}");
        }
    }

    public function clearCategoryCache(): void
    {
        Cache::forget('categories_active');

        if ($this->category_id) {
            Cache::forget("category_products_{$this->category_id}");
        }
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bike(): BelongsTo
    {
        return $this->belongsTo(Bike::class);
    }

    public function getManufacturerAttribute(): ?string
    {
        return $this->bike?->manufacturer;
    }

    public function getModelAttribute(): ?string
    {
        return $this->bike?->model;
    }

    public function getYearAttribute(): ?int
    {
        return $this->bike?->year;
    }

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
        // Only include active products
        if (! $this->is_active) {
            return [];
        }

        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category_name' => $this->category?->name,
            'manufacturer' => $this->manufacturer,
            'model' => $this->model,
            'year' => (string) $this->year,
            'type' => $this->type,
            'price' => (float) $this->price,
            'is_active' => $this->is_active,
            'category_id' => (int) $this->category_id,
            'bike_id' => (int) $this->bike_id,
            'sku' => $this->sku,
            'stock_quantity' => $this->stock_quantity,
            'image' => $this->image,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->is_active;
    }

    /**
     * Get local image URL with fallback to original.
     */
    public function getLocalImageUrlAttribute(): ?string
    {
        if ($this->image_local_path && $this->image_hosted_locally) {
            return asset("storage/products/original/{$this->image_local_path}");
        }

        return null;
    }

    /**
     * Get the best available image URL with fallback.
     */
    public function getBestImageUrlAttribute(): string
    {
        // Prefer local image if available
        if ($this->image_hosted_locally && $this->image_local_path) {
            return asset("storage/products/original/{$this->image_local_path}");
        }

        // Fallback to original URL
        return $this->image;
    }
}
