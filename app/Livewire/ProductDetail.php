<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.simple')]
class ProductDetail extends Component
{
    public ?Product $product = null;

    public ?ProductVariant $selectedVariant = null;

    public bool $showWishlistLoginPrompt = false;

    private ?bool $wishlistStatus = null;

    public function mount(Product $product): void
    {
        $this->product = $product->load(['category', 'bike', 'activeVariants']);
        $this->loadWishlistStatus();

        if ($this->product->has_variants && $this->product->activeVariants->isNotEmpty()) {
            $this->selectedVariant = $this->product->activeVariants->first();
        }
    }

    public function selectVariant(ProductVariant $variant): void
    {
        $this->selectedVariant = $variant;
    }

    public function getCurrentPriceProperty(): float
    {
        if ($this->selectedVariant && $this->selectedVariant->price !== null) {
            return (float) $this->selectedVariant->price;
        }

        return (float) ($this->product->price ?? 0);
    }

    public function getCurrentStockProperty(): int
    {
        if ($this->selectedVariant) {
            return $this->selectedVariant->stock_quantity;
        }

        return $this->product->stock_quantity ?? 0;
    }

    public function getIsInStockProperty(): bool
    {
        return $this->current_stock > 0;
    }

    public function getCurrentSkuProperty(): string
    {
        if ($this->selectedVariant) {
            return $this->selectedVariant->full_sku;
        }

        return $this->product->sku ?? '';
    }

    private function loadWishlistStatus(): void
    {
        if (! Auth::check() || ! $this->product) {
            $this->wishlistStatus = false;

            return;
        }

        $this->wishlistStatus = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->exists();
    }

    public function dismissWishlistPrompt(): void
    {
        $this->showWishlistLoginPrompt = false;
    }

    public function getIsInWishlistProperty(): bool
    {
        return $this->wishlistStatus ?? false;
    }

    public function toggleWishlist(): void
    {
        if (! $this->product) {
            return;
        }

        if (! Auth::check()) {
            $this->showWishlistLoginPrompt = true;

            return;
        }

        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($existingWishlist) {
            $existingWishlist->delete();
            $this->wishlistStatus = false;
            $this->dispatch('flash', [
                'type' => 'info',
                'message' => 'Product removed from wishlist.',
            ]);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
            ]);
            $this->wishlistStatus = true;
            $this->dispatch('flash', [
                'type' => 'success',
                'message' => 'Product added to wishlist!',
            ]);
        }

        $this->dispatch('wishlist-updated');
    }

    public function getTitleProperty(): string
    {
        if (! $this->product) {
            return config('app.name', 'Bike Shop');
        }

        $parts = [];
        if ($this->product->manufacturer) {
            $parts[] = $this->product->manufacturer;
        }
        if ($this->product->model) {
            $parts[] = $this->product->model;
        }
        $parts[] = $this->product->name;

        return implode(' | ', $parts).' | '.config('app.name', 'Bike Shop');
    }

    public function getMetaDescriptionProperty(): string
    {
        if (! $this->product) {
            return 'View product details';
        }

        $description = $this->product->description ?? $this->product->name;

        if ($this->current_price) {
            $description .= ' - Only $'.number_format($this->current_price, 2);
        }

        if ($this->selectedVariant) {
            $description .= ' - '.$this->selectedVariant->name;
        }

        return substr($description, 0, 160);
    }

    public function getMetaKeywordsProperty(): array
    {
        if (! $this->product) {
            return [];
        }

        $keywords = [
            $this->product->name,
            $this->product->type,
        ];

        if ($this->product->manufacturer) {
            $keywords[] = $this->product->manufacturer;
        }

        if ($this->product->model) {
            $keywords[] = $this->product->model;
        }

        if ($this->product->category) {
            $keywords[] = $this->product->category->name;
        }

        return array_filter($keywords);
    }

    public function addToCart(): void
    {
        if (! $this->product) {
            return;
        }

        if ($this->product->has_variants && ! $this->selectedVariant) {
            $this->dispatch('flash', [
                'type' => 'error',
                'message' => 'Please select a variant first.',
            ]);

            return;
        }

        $this->dispatch('addToCart', [
            'productId' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->current_price,
            'image' => $this->product->image,
            'variantId' => $this->selectedVariant?->id,
            'variantName' => $this->selectedVariant?->name,
            'sku' => $this->current_sku,
        ]);
    }

    public function getBreadcrumbItemsProperty(): array
    {
        $items = [];

        if ($this->product->bike && $this->product->bike->is_active) {
            // Add manufacturer breadcrumb
            $items[] = [
                'label' => $this->product->bike->manufacturer,
                'url' => route('bikes.manufacturer', [
                    'manufacturer' => strtolower($this->product->bike->manufacturer),
                ]),
            ];

            // Add model breadcrumb
            $items[] = [
                'label' => $this->product->bike->model,
                'url' => route('bikes.model', [
                    'manufacturer' => strtolower($this->product->bike->manufacturer),
                    'model' => str_replace(' ', '-', strtolower($this->product->bike->model)),
                ]),
            ];

            // Add year breadcrumb (clickable)
            $items[] = [
                'label' => (string) $this->product->bike->year,
                'url' => route('bikes.show', [
                    'manufacturer' => strtolower($this->product->bike->manufacturer),
                    'model' => str_replace(' ', '-', strtolower($this->product->bike->model)),
                    'year' => $this->product->bike->year,
                ]),
            ];
        }

        // Add product name as final breadcrumb
        $items[] = [
            'label' => $this->product->name,
            'url' => null,
        ];

        return $items;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.product-detail', [
            'title' => $this->title,
            'metaDescription' => $this->metaDescription,
            'metaKeywords' => $this->metaKeywords,
            'breadcrumbItems' => $this->breadcrumbItems,
            'isInWishlist' => $this->isInWishlist,
            'currentPrice' => $this->current_price,
            'currentStock' => $this->current_stock,
            'isInStock' => $this->isInStock,
            'currentSku' => $this->current_sku,
        ]);
    }
}
