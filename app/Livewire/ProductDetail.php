<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.simple')]
class ProductDetail extends Component
{
    public ?Product $product = null;

    public function mount(Product $product): void
    {
        $this->product = $product->load('category');
    }

    public function addToCart(): void
    {
        if (! $this->product) {
            return;
        }

        \Log::info('ProductDetail::addToCart called', ['productId' => $this->product->id, 'productName' => $this->product->name]);

        $this->dispatch('addToCart', [
            'productId' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'image' => $this->product->image,
        ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.product-detail');
    }
}
