<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ImageDownloadService
{
    private array $downloadedImages = [];

    private array $failedDownloads = [];

    /**
     * Download all Unsplash URLs used in products
     */
    public function downloadAllProductImages(): array
    {
        $unsplashUrls = $this->getAllUnsplashUrls();

        foreach ($unsplashUrls as $url) {
            $this->downloadAndStoreImage($url);
        }

        return [
            'downloaded' => $this->downloadedImages,
            'failed' => $this->failedDownloads,
        ];
    }

    /**
     * Download and store a single image
     */
    private function downloadAndStoreImage(string $url): bool
    {
        try {
            $client = new Client(['timeout' => 30]);
            $response = $client->get($url);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("HTTP {$response->getStatusCode()}");
            }

            $filename = $this->generateFilename($url);
            $extension = $this->getFileExtension($response->getHeaderLine('Content-Type'));

            Storage::disk('products')->put(
                "original/{$filename}{$extension}",
                $response->getBody()->getContents()
            );

            $this->downloadedImages[$url] = $filename.$extension;

            return true;

        } catch (\Exception $e) {
            $this->failedDownloads[$url] = $e->getMessage();

            return false;
        }
    }

    /**
     * Generate unique filename for image
     */
    private function generateFilename(string $url): string
    {
        $hash = md5($url);
        $timestamp = now()->format('Y-m-d-H-i-s');

        return "bike-product-{$timestamp}-{$hash}";
    }

    /**
     * Get file extension from content type
     */
    private function getFileExtension(?string $contentType): string
    {
        return match ($contentType) {
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/webp' => '.webp',
            default => '.jpg'
        };
    }

    /**
     * Get all Unsplash URLs from factory and seeder
     */
    private function getAllUnsplashUrls(): array
    {
        // From ProductFactory.php
        $factoryUrls = [
            'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1558981033-0f0309287805?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1571188654248-7a89213915f11?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1591635333245-7f322d95aee8?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1600712242805-5f78671b24da?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1621296115628-662905474717?w=400&h=400&fit=crop',
        ];

        // From ProductImagesSeeder.php
        $seederUrls = [
            'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1541625602330-2277a4c46182?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1507035895480-2b3156c3113a?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1558059815-451b67423313?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1544191696-102dbdaeeaa0?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=400&h=400&fit=crop',
        ];

        return array_unique(array_merge($factoryUrls, $seederUrls));
    }
}
