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

class ProcessSmartImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = [5, 10, 30];

    public function __construct(
        public Product $product
    ) {
        $this->product = $product;
    }

    public function handle(SmartImageService $smartImageService): void
    {
        try {
            Log::info('Processing smart image for product', [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
            ]);

            $smartImageUrl = $smartImageService->getSmartImageUrl($this->product);

            if ($smartImageUrl) {
                Log::info('Successfully fetched smart image', [
                    'product_id' => $this->product->id,
                    'url' => $smartImageUrl,
                ]);
            } else {
                Log::warning('Failed to fetch smart image', [
                    'product_id' => $this->product->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Smart image processing failed', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Smart image job failed permanently', [
            'product_id' => $this->product->id,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
