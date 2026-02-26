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

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $this->dispatch('addToCart', [
            'productId' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
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
