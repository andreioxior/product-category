<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.simple')]
class ProductDetail extends Component
{
    public ?Product $product = null;

    public function mount(Product $product): void
    {
        $this->product = $product->load(['category', 'bike']);
    }

    public function addToCart(): void
    {
        if (! $this->product) {
            return;
        }

        Log::info('ProductDetail::addToCart called', ['productId' => $this->product->id, 'productName' => $this->product->name]);

        $this->dispatch('addToCart', [
            'productId' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'image' => $this->product->image,
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
            'breadcrumbItems' => $this->breadcrumbItems,
        ]);
    }
}
