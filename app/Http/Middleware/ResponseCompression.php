<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResponseCompression
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only compress HTML responses
        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            // Check if client accepts gzip
            $acceptEncoding = $request->header('Accept-Encoding');

            if (strpos($acceptEncoding, 'gzip') !== false) {
                // Get response content
                $content = $response->getContent();

                // Only compress if content is larger than 1KB and not already compressed
                if (strlen($content) > 1024 && ! str_contains($response->headers->get('Content-Encoding', ''), 'gzip')) {
                    $compressed = gzencode($content, 6);

                    // Only use compression if it actually reduces size
                    if (strlen($compressed) < strlen($content)) {
                        $response->setContent($compressed);
                        $response->headers->set('Content-Encoding', 'gzip');
                        $response->headers->set('Content-Length', strlen($compressed));

                        Log::info('Response compressed', [
                            'original_size' => strlen($content),
                            'compressed_size' => strlen($compressed),
                            'compression_ratio' => round((1 - strlen($compressed) / strlen($content)) * 100, 2).'%',
                        ]);
                    }
                }
            }
        }

        return $response;
    }
}
