<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public const CACHE_TTL_5_MINUTES = 300;

    public const CACHE_TTL_15_MINUTES = 900;

    public const CACHE_TTL_30_MINUTES = 1800;

    public const CACHE_TTL_1_HOUR = 3600;

    public const CACHE_TTL_3_HOURS = 10800;

    public const CACHE_TTL_6_HOURS = 21600;

    public const CACHE_TTL_12_HOURS = 43200;

    public const CACHE_TTL_24_HOURS = 86400;

    public static function categories(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Cache::remember('categories_active', self::CACHE_TTL_6_HOURS, function () {
            return \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        });
    }

    public static function totalProductsCount(): int
    {
        return Cache::remember('total_products_count', self::CACHE_TTL_1_HOUR, function () {
            return \App\Models\Product::where('is_active', true)->count();
        });
    }

    public static function bikeManufacturers(): array
    {
        return Cache::remember('bike_manufacturers', self::CACHE_TTL_12_HOURS, function () {
            return \App\Models\Product::whereHas('bike')
                ->join('bikes', 'products.bike_id', '=', 'bikes.id')
                ->distinct()
                ->orderBy('bikes.manufacturer')
                ->pluck('bikes.manufacturer')
                ->toArray();
        });
    }

    public static function bikeModels(string $manufacturer): array
    {
        return Cache::remember("bike_models_{$manufacturer}", self::CACHE_TTL_6_HOURS, function () use ($manufacturer) {
            return \App\Models\Product::whereHas('bike', function ($query) use ($manufacturer) {
                $query->where('manufacturer', $manufacturer);
            })
                ->join('bikes', 'products.bike_id', '=', 'bikes.id')
                ->distinct()
                ->orderBy('bikes.model')
                ->pluck('bikes.model')
                ->toArray();
        });
    }

    public static function bikeYears(string $manufacturer, string $model): array
    {
        return Cache::remember("bike_years_{$manufacturer}_{$model}", self::CACHE_TTL_3_HOURS, function () use ($manufacturer, $model) {
            return \App\Models\Product::whereHas('bike', function ($query) use ($manufacturer, $model) {
                $query->where('manufacturer', $manufacturer)
                    ->where('model', $model);
            })
                ->join('bikes', 'products.bike_id', '=', 'bikes.id')
                ->distinct()
                ->orderByDesc('bikes.year')
                ->pluck('bikes.year')
                ->toArray();
        });
    }

    public static function homepageFeaturedProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('homepage_featured_products', self::CACHE_TTL_30_MINUTES, function () {
            return \App\Models\Product::query()
                ->with(['category', 'bike'])
                ->where('is_active', true)
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();
        });
    }

    public static function homepageNewArrivals(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('homepage_new_arrivals', self::CACHE_TTL_1_HOUR, function () {
            return \App\Models\Product::query()
                ->with(['category', 'bike'])
                ->where('is_active', true)
                ->orderByDesc('created_at')
                ->limit(4)
                ->get();
        });
    }

    public static function homepagePromoProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('homepage_promo_products', self::CACHE_TTL_1_HOUR, function () {
            return \App\Models\Product::query()
                ->with(['category', 'bike'])
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(4)
                ->get();
        });
    }

    public static function clearCategoryCache(?int $categoryId = null): void
    {
        Cache::forget('categories_active');

        if ($categoryId) {
            Cache::forget("category_products_{$categoryId}");
        }
    }

    public static function clearProductCache(?int $bikeId = null, ?string $manufacturer = null, ?string $model = null): void
    {
        Cache::forget('total_products_count');
        Cache::forget('bike_manufacturers');

        if ($manufacturer) {
            Cache::forget("bike_models_{$manufacturer}");
        }

        if ($manufacturer && $model) {
            Cache::forget("bike_years_{$manufacturer}_{$model}");
        }
    }

    public static function clearHomepageCache(): void
    {
        Cache::forget('homepage_featured_products');
        Cache::forget('homepage_new_arrivals');
        Cache::forget('homepage_promo_products');
    }
}
