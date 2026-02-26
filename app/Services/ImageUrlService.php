<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImageUrlService
{
    public static function getProductImageUrl(Product $product, ?int $width = null, ?int $height = null): string
    {
        $originalImage = $product->image ?? null;

        if ($originalImage && self::isValidImageUrl($originalImage)) {
            return $originalImage;
        }

        try {
            $smartImageService = app(SmartImageService::class);
            $smartUrl = $smartImageService->getSmartImageUrl($product);

            if ($smartUrl) {
                return $smartUrl;
            }
        } catch (\Exception $e) {
            Log::warning('Smart image service failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
        }

        return self::getPlaceholderUrl($product->name ?? 'product', $width, $height);
    }

    private static function isValidImageUrl(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    public static function getPlaceholderUrl(string $seed = 'product', ?int $width = null, ?int $height = null): string
    {
        $size = $width && $height ? "{$width}x{$height}" : '300x300';

        return 'https://picsum.photos/seed/'.urlencode($seed)."/{$size}.jpg";
    }

    public static function localImageExists(string $src): bool
    {
        if (! $src) {
            return false;
        }

        $cacheKey = 'local_image_exists_'.md5($src);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($src) {
            return file_exists(public_path('images/products/'.basename($src)));
        });
    }
}
