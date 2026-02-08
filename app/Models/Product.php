<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'type',
        'price',
        'sku',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'bike_id' => 'integer',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

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
}
