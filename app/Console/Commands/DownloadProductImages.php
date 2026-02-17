<?php

namespace App\Console\Commands;

use App\Services\ImageDownloadService;
use Illuminate\Console\Command;

class DownloadProductImages extends Command
{
    protected $signature = 'products:download-images {--force : Re-download existing images}';

    protected $description = 'Download all Unsplash product images and store them locally';

    public function handle(ImageDownloadService $downloadService): int
    {
        $this->info('Starting product image download...');

        $results = $downloadService->downloadAllProductImages();

        $this->info("\n✅ Successfully downloaded: ".count($results['downloaded']).' images');
        $this->info('❌ Failed downloads: '.count($results['failed']).' images');

        if (! empty($results['failed'])) {
            $this->error("\nFailed URLs:");
            foreach ($results['failed'] as $url => $error) {
                $this->line("  - {$url}: {$error}");
            }
        }

        $this->info("\nImage download completed!");

        return Command::SUCCESS;
    }
}
