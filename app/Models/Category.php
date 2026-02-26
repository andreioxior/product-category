<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (Category $category) {
            CacheService::clearCategoryCache();
        });

        static::updated(function (Category $category) {
            CacheService::clearCategoryCache();
        });

        static::deleted(function (Category $category) {
            CacheService::clearCategoryCache();
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
