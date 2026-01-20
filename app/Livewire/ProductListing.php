<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.simple')]
class ProductListing extends Component
{
    use WithPagination;

    #[Locked]
    public array $sortOptions = [
        'name_asc' => 'Name (A-Z)',
        'name_desc' => 'Name (Z-A)',
        'price_asc' => 'Price (Low to High)',
        'price_desc' => 'Price (High to Low)',
        'manufacturer_asc' => 'Manufacturer (A-Z)',
    ];

    #[Url]
    public string $sort = 'name_asc';

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $type = null;

    #[Url]
    public ?string $manufacturer = null;

    #[Url]
    public ?string $search = null;

    #[Url]
    public ?int $minPrice = null;

    #[Url]
    public ?int $maxPrice = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedManufacturer(): void
    {
        $this->resetPage();
    }

    public function updatedMinPrice(): void
    {
        $this->resetPage();
    }

    public function updatedMaxPrice(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = null;
        $this->category = null;
        $this->type = null;
        $this->manufacturer = null;
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->sort = 'name_asc';
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        \Log::info('ProductListing::addToCart called', ['productId' => $productId, 'productName' => $product->name]);

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
            ->with('category')
            ->where('is_active', true)
            ->when($this->search, fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            ->when($this->category, fn ($query) => $query->where('category_id', $this->category))
            ->when($this->type, fn ($query) => $query->where('type', $this->type))
            ->when($this->manufacturer, fn ($query) => $query->where('manufacturer', $this->manufacturer))
            ->when($this->minPrice, fn ($query) => $query->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn ($query) => $query->where('price', '<=', $this->maxPrice))
            ->when($this->sort === 'name_asc', fn ($query) => $query->orderBy('name'))
            ->when($this->sort === 'name_desc', fn ($query) => $query->orderByDesc('name'))
            ->when($this->sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($this->sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($this->sort === 'manufacturer_asc', fn ($query) => $query->orderBy('manufacturer'))
            ->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)->orderBy('name')->get();
    }

    public function getAvailableTypesProperty()
    {
        return Product::where('is_active', true)
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
    }

    public function getAvailableManufacturersProperty()
    {
        return Product::where('is_active', true)
            ->select('manufacturer')
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
    }

    public function getPriceRangeProperty()
    {
        return Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
    }

    public function render(): \Illuminate\View\View
    {
        $filteredProducts = $this->getFilteredProducts();

        return view('livewire.product-listing', [
            'filteredProducts' => $filteredProducts,
            'categories' => $this->categories,
            'availableTypes' => $this->availableTypes,
            'availableManufacturers' => $this->availableManufacturers,
        ]);
    }
}
