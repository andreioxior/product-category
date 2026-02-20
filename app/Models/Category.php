<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

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
            Cache::forget('categories_active');
        });

        static::updated(function (Category $category) {
            Cache::forget('categories_active');
        });

        static::deleted(function (Category $category) {
            Cache::forget('categories_active');
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
