<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'type',
        'price',
        'sku_suffix',
        'stock_quantity',
        'is_active',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'stock_quantity' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFullSkuAttribute(): string
    {
        if (! $this->sku_suffix || ! $this->product) {
            return $this->product?->sku ?? '';
        }

        return $this->product->sku.$this->sku_suffix;
    }

    public function getDisplayPriceAttribute(): float
    {
        return $this->price ?? $this->product?->price ?? 0;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }
}
