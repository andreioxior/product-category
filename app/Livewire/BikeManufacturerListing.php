<?php

namespace App\Livewire;

use App\Models\Bike;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.simple')]
class BikeManufacturerListing extends Component
{
    use WithPagination;

    #[Locked]
    public string $manufacturer;

    #[Locked]
    public Bike $manufacturerBike;

    #[Locked]
    public array $sortOptions = [
        'name_asc' => 'Name (A-Z)',
        'name_desc' => 'Name (Z-A)',
        'price_asc' => 'Price (Low to High)',
        'price_desc' => 'Price (High to Low)',
        'model_asc' => 'Model (A-Z)',
    ];

    #[Url]
    public string $sort = 'name_asc';

    public function mount(string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
        $this->manufacturerBike = Bike::whereRaw('LOWER(manufacturer) = ?', [strtolower($manufacturer)])
            ->active()
            ->firstOrFail();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
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

    public function getFilteredProducts(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Product::query()
            ->with(['category', 'bike'])
            ->whereHas('bike', fn ($query) => $query->whereRaw('LOWER(manufacturer) = ?', [strtolower($this->manufacturer)]))
            ->where('is_active', true)
            ->when($this->sort === 'name_asc', fn ($query) => $query->orderBy('name'))
            ->when($this->sort === 'name_desc', fn ($query) => $query->orderByDesc('name'))
            ->when($this->sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($this->sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($this->sort === 'model_asc', fn ($query) => $query->join('bikes', 'products.bike_id', '=', 'bikes.id')->orderBy('bikes.model'))
            ->paginate(12);
    }

    public function getAvailableModelsProperty()
    {
        return Bike::active()
            ->select('model')
            ->whereRaw('LOWER(manufacturer) = ?', [strtolower($this->manufacturer)])
            ->distinct()
            ->orderBy('model')
            ->pluck('model');
    }

    public function render(): \Illuminate\View\View
    {
        $filteredProducts = $this->getFilteredProducts();

        return view('livewire.bike-manufacturer-listing', [
            'filteredProducts' => $filteredProducts,
        ]);
    }
}
