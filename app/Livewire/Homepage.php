<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CacheService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Homepage extends Component
{
    public function getFeaturedProductsProperty()
    {
        return CacheService::homepageFeaturedProducts();
    }

    public function getCategoriesProperty()
    {
        return CacheService::categories();
    }

    public function getNewArrivalsProperty()
    {
        return CacheService::homepageNewArrivals();
    }

    public function getPromoProductsProperty()
    {
        return CacheService::homepagePromoProducts();
    }

    public function getTotalProductsCountProperty(): int
    {
        return CacheService::totalProductsCount();
    }

    public function addToCart(int $productId, ?int $variantId = null, ?string $variantName = null, ?float $price = null, ?string $sku = null): void
    {
        $product = Product::with('activeVariants')->find($productId);
        if (! $product) {
            return;
        }

        $this->dispatch('addToCart', [
            'productId' => $product->id,
            'name' => $product->name,
            'price' => $price ?? $product->price,
            'image' => $product->image,
            'variantId' => $variantId,
            'variantName' => $variantName,
            'sku' => $sku,
        ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.homepage', [
            'featuredProducts' => $this->featuredProducts,
            'categories' => $this->categories,
            'newArrivals' => $this->newArrivals,
            'promoProducts' => $this->promoProducts,
            'totalProductsCount' => $this->totalProductsCount,
        ]);
    }
}
