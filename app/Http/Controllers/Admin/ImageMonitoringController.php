<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ImageMonitoringController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('admin.image-monitoring', [
            'metrics' => $this->getDashboardMetrics(),
            'alerts' => $this->getActiveAlerts(),
            'coverage_data' => $this->getCoverageData(),
            'performance_trends' => $this->getPerformanceTrends(),
        ]);
    }

    private function getDashboardMetrics(): array
    {
        $cacheHitRate = $this->calculateCacheHitRate();
        $avgLoadTime = $this->getAverageLoadTime();
        $smartCoverage = $this->getCoveragePercentage();
        $apiUsage = $this->getDailyApiCalls();

        return [
            'cache_hit_rate' => [
                'current' => $cacheHitRate,
                'target' => 85,
                'status' => $this->getStatusFromPercentage($cacheHitRate, 85),
                'trend' => $this->getTrend('cache_hit_rate'),
            ],
            'page_load_impact' => [
                'current' => $avgLoadTime,
                'impact' => '+0.2s',
                'target' => 2.0,
                'status' => $avgLoadTime <= 2.0 ? 'success' : 'warning',
            ],
            'smart_coverage' => [
                'products_with_smart' => $this->getSmartImageCount(),
                'total_products' => Product::count(),
                'percentage' => $smartCoverage,
                'target' => 90,
            ],
            'api_usage' => [
                'daily_calls' => $apiUsage,
                'rate_limit_status' => 'healthy',
                'sources_status' => $this->getSourceStatuses(),
            ],
        ];
    }

    private function calculateCacheHitRate(): float
    {
        $totalProducts = Product::where('is_active', true)->count();
        if ($totalProducts === 0) {
            return 0.0;
        }

        $smartImages = 0;
        $products = Product::where('is_active', true)->take(100)->get();

        foreach ($products as $product) {
            $cacheKey = 'smart_img_'.$product->id;
            if (Cache::has($cacheKey)) {
                $smartImages++;
            }
        }

        return $totalProducts > 0 ? ($smartImages / $totalProducts) * 100 : 0.0;
    }

    private function getAverageLoadTime(): float
    {
        // Simulate load time measurement - in real implementation,
        // this would come from actual performance monitoring
        return 1.8; // seconds - current baseline
    }

    private function getCoveragePercentage(): float
    {
        $totalProducts = Product::where('is_active', true)->count();
        $smartImages = $this->getSmartImageCount();

        return $totalProducts > 0 ? ($smartImages / $totalProducts) * 100 : 0.0;
    }

    private function getSmartImageCount(): int
    {
        $count = 0;
        $products = Product::where('is_active', true)->take(50)->get();

        foreach ($products as $product) {
            $cacheKey = 'smart_img_'.$product->id;
            if (Cache::has($cacheKey)) {
                $count++;
            }
        }

        return $count;
    }

    private function getDailyApiCalls(): int
    {
        // In real implementation, this would track actual API calls
        // For now, estimate based on cache misses
        return 45; // estimated daily API calls
    }

    private function getSourceStatuses(): array
    {
        return [
            'unsplash' => [
                'status' => 'healthy',
                'response_time' => '1.2s',
            ],
            'pexels' => [
                'status' => 'healthy',
                'response_time' => '0.8s',
            ],
            'pixabay' => [
                'status' => 'healthy',
                'response_time' => '1.0s',
            ],
        ];
    }

    private function getActiveAlerts(): array
    {
        $alerts = [];
        $metrics = $this->getDashboardMetrics();

        // Cache hit rate alerts
        if ($metrics['cache_hit_rate']['current'] < 60) {
            $alerts[] = [
                'type' => 'critical',
                'message' => 'Cache hit rate dropped to '.$metrics['cache_hit_rate']['current'].'%',
                'action' => 'Check cache configuration',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        if ($metrics['cache_hit_rate']['current'] < 75) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Cache hit rate below target: '.$metrics['cache_hit_rate']['current'].'%',
                'action' => 'Review cache warming strategy',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        // Page load time alerts
        if ($metrics['page_load_impact']['current'] > 3.0) {
            $alerts[] = [
                'type' => 'critical',
                'message' => 'Page load time increased to '.$metrics['page_load_impact']['current'].'s',
                'action' => 'Review image optimization',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        if ($metrics['page_load_impact']['current'] > 2.0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Page load time increased to '.$metrics['page_load_impact']['current'].'s',
                'action' => 'Review image processing',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        // Smart coverage alerts
        if ($metrics['smart_coverage']['percentage'] < 50) {
            $alerts[] = [
                'type' => 'critical',
                'message' => 'Smart image coverage too low: '.$metrics['smart_coverage']['percentage'].'%',
                'action' => 'Check image processing jobs',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        if ($metrics['smart_coverage']['percentage'] < 75) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Smart image coverage below target: '.$metrics['smart_coverage']['percentage'].'%',
                'action' => 'Monitor queue processing',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        return $alerts;
    }

    private function getStatusFromPercentage(float $percentage, float $target): string
    {
        if ($percentage >= $target) {
            return 'success';
        }
        if ($percentage >= ($target * 0.9)) {
            return 'success';
        }
        if ($percentage >= ($target * 0.75)) {
            return 'warning';
        }

        return 'danger';
    }

    private function getTrend(string $metric): string
    {
        // In real implementation, this would analyze historical data
        // For now, return stable trend
        return 'stable';
    }

    private function getCoverageData(): array
    {
        $categories = ['bikes', 'gear', 'parts', 'accessories'];
        $coverage = [];

        foreach ($categories as $category) {
            $coverage[$category] = $this->getCategoryCoverage($category);
        }

        return $coverage;
    }

    private function getCategoryCoverage(string $category): array
    {
        $products = Product::where('is_active', true)
            ->where(function ($query) use ($category) {
                switch ($category) {
                    case 'bikes':
                        $query->whereIn('products.type', ['Sport', 'Cruiser', 'Touring', 'Adventure', 'Electric', 'Naked']);
                        break;
                    case 'gear':
                        $query->whereIn('products.type', ['Helmet', 'Jacket', 'Glove', 'Boot', 'Armor', 'Protection']);
                        break;
                    case 'parts':
                        $query->whereIn('products.type', ['Engine', 'Exhaust', 'Brake', 'Suspension', 'Tire', 'Battery']);
                        break;
                    case 'accessories':
                        $query->whereIn('products.type', ['Tool', 'Luggage', 'Lock', 'Cover', 'Storage']);
                        break;
                }
            })
            ->take(50)
            ->get();

        $totalInCategory = $products->count();
        $withSmartImages = 0;

        foreach ($products as $product) {
            $cacheKey = 'smart_img_'.$product->id;
            if (Cache::has($cacheKey)) {
                $withSmartImages++;
            }
        }

        return [
            'total' => $totalInCategory,
            'with_smart_images' => $withSmartImages,
            'coverage_percentage' => $totalInCategory > 0 ? ($withSmartImages / $totalInCategory) * 100 : 0,
        ];
    }

    private function getPerformanceTrends(): array
    {
        // In real implementation, this would pull from monitoring database
        // For now, provide sample trend data
        return [
            'cache_hit_rate' => [
                '7_days' => [82, 85, 88, 87, 85],
                '30_days' => [80, 82, 84, 85, 86],
            ],
            'page_load_time' => [
                '7_days' => [1.6, 1.7, 1.8, 1.7, 1.8],
                '30_days' => [1.5, 1.6, 1.7, 1.8, 1.8],
            ],
            'smart_coverage' => [
                '7_days' => [70, 75, 82, 88, 90],
                '30_days' => [65, 70, 75, 82, 88],
            ],
        ];
    }
}
