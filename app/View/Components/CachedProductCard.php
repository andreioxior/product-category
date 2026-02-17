<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class CachedProductCard extends Component
{
    public Product $product;

    public string $cacheKey;

    /**
     * Create a new component instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->cacheKey = "product_card_{$product->id}_".md5($product->updated_at->timestamp);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        // Cache individual product cards for 15 minutes
        return Cache::store('products')->remember(
            $this->cacheKey,
            now()->addMinutes(15),
            fn () => view('components.cached-product-card', ['product' => $this->product])->render()
        );
    }
}
