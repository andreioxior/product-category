<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageUrlService
{
    /**
     * Get optimized image URL for a product with smart image support.
     */
    public static function getProductImageUrl(Product $product, ?int $width = null, ?int $height = null): string
    {
        // Use original image if available
        $originalImage = $product->image ?? null;

        if ($originalImage && self::isValidImageUrl($originalImage)) {
            return $originalImage;
        }

        // Try smart image as fallback (non-blocking for performance)
        try {
            $smartImageService = app(SmartImageService::class);
            $smartUrl = $smartImageService->getSmartImageUrl($product);

            if ($smartUrl) {
                return $smartUrl;
            }
        } catch (\Exception $e) {
            // Log smart image failure but don't block page load
            Log::warning('Smart image service failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Final fallback to placeholder
        return self::getPlaceholderUrl($product->name ?? 'product', $width, $height);
    }

    /**
     * Check if URL is a valid image URL.
     */
    private static function isValidImageUrl(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    /**
     * Get placeholder image URL.
     */
    public static function getPlaceholderUrl(string $seed = 'product', ?int $width = null, ?int $height = null): string
    {
        $size = $width && $height ? "{$width}x{$height}" : '300x300';

        return 'https://picsum.photos/seed/'.urlencode($seed)."/{$size}.jpg";
    }

    /**
     * Check if image path is a local file.
     */
    private static function isLocalImage(string $path): bool
    {
        return ! str_starts_with($path, 'http') &&
               ! str_starts_with($path, '//') &&
               ! str_contains($path, 'picsum.photos');
    }

    /**
     * Check if local image file exists with caching.
     */
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

    /**
     * Get local image URL with proper path checking.
     */
    private static function getLocalImageUrl(string $path, ?int $width = null, ?int $height = null): string
    {
        // Remove any leading slashes to ensure consistent path
        $cleanPath = ltrim($path, '/');

        // Check if file exists in products directory
        if (Storage::disk('public')->exists('products/'.basename($cleanPath))) {
            $url = asset('storage/products/'.basename($cleanPath));

            // Add query parameters for sizing
            if ($width || $height) {
                $params = [];
                if ($width) {
                    $params[] = "w={$width}";
                }
                if ($height) {
                    $params[] = "h={$height}";
                }
                $url .= '?'.implode('&', $params);
            }

            return $url;
        }

        // Fall back to placeholder
        return self::getPlaceholderUrl('product', $width, $height);
    }
}
