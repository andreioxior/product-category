<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\SmartImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WarmImageCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = [5, 10, 30];

    public function handle(SmartImageService $smartImageService): void
    {
        Log::info('Starting image cache warming');

        // Warm cache for popular products first
        $popularProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        foreach ($popularProducts as $product) {
            try {
                $smartUrl = $smartImageService->getSmartImageUrl($product);
                if ($smartUrl) {
                    Log::info('Warmed cache for product', [
                        'product_id' => $product->id,
                        'smart_url' => $smartUrl,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to warm cache for product', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Image cache warming completed');
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Image cache warming job failed', [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
