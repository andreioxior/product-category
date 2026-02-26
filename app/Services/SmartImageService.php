<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SmartImageService
{
    public function getSmartImageUrl(Product $product): ?string
    {
        try {
            $cacheKey = 'smart_img_'.$product->id;

            return Cache::remember($cacheKey, now()->hours(24), function () use ($product) {
                return $this->fetchSmartImage($product);
            });
        } catch (\Exception $e) {
            Log::warning('Smart image service error', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function fetchSmartImage(Product $product): ?string
    {
        $manufacturer = $product->bike?->manufacturer ?? 'motorcycle';
        $model = $product->bike?->model ?? '';
        $type = $product->type ?? 'accessory';
        $query = "motorcycle {$manufacturer} {$model} {$type} professional photography";

        $unsplashUrl = 'https://api.unsplash.com/photos/random?query='.urlencode($query).'&count=1&orientation=landscape';

        try {
            $unsplashData = Http::timeout(10)->withHeaders([
                'Authorization' => 'Client-ID '.config('services.unsplash.access_key'),
            ])->get($unsplashUrl)->json();
            if (! empty($unsplashData) && isset($unsplashData[0]['urls']['regular'])) {
                $imageUrl = $unsplashData[0]['urls']['regular'];

                return $this->downloadAndStoreImage($imageUrl, $product);
            }
        } catch (\Exception $e) {
            Log::debug('Unsplash API error', ['error' => $e->getMessage()]);
        }

        $pexelsUrl = 'https://api.pexels.com/v1/search?query='.urlencode($query).'&per_page=1&orientation=landscape';

        try {
            $pexelsData = Http::timeout(10)->withHeaders([
                'Authorization' => config('services.pexels.api_key'),
            ])->get($pexelsUrl)->json();
            if (! empty($pexelsData['photos']) && isset($pexelsData['photos'][0]['src']['large'])) {
                $imageUrl = $pexelsData['photos'][0]['src']['large'];

                return $this->downloadAndStoreImage($imageUrl, $product);
            }
        } catch (\Exception $e) {
            Log::debug('Pexels API error', ['error' => $e->getMessage()]);
        }

        $pixabayUrl = 'https://pixabay.com/api/?key='.config('services.pixabay.api_key').'&q='.urlencode($query).'&per_page=1&image_type=photo&orientation=horizontal';

        try {
            $pixabayData = Http::timeout(10)->get($pixabayUrl)->json();
            if (! empty($pixabayData['hits']) && isset($pixabayData['hits'][0]['webformatURL'])) {
                $imageUrl = $pixabayData['hits'][0]['webformatURL'];

                return $this->downloadAndStoreImage($imageUrl, $product);
            }
        } catch (\Exception $e) {
            Log::debug('Pixabay API error', ['error' => $e->getMessage()]);
        }

        return null;
    }

    private function downloadAndStoreImage(string $imageUrl, Product $product): ?string
    {
        try {
            $imageData = Http::timeout(10)->get($imageUrl);
            $imageContents = (string) $imageData;
            $filename = 'smart_'.$product->id.'_unsplash.jpg';
            $path = 'products/smart/'.$filename;

            Storage::disk('public')->put($path, $imageContents);

            return asset('storage/'.$path);
        } catch (\Exception $e) {
            Log::warning('Failed to download smart image', [
                'product_id' => $product->id,
                'url' => $imageUrl,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function clearProductCache(Product $product): void
    {
        Cache::forget('smart_img_'.$product->id);
    }

    public function warmCache(): void
    {
        $products = Product::where('is_active', true)->take(20)->get();
        foreach ($products as $product) {
            $this->getSmartImageUrl($product);
        }
    }
}
