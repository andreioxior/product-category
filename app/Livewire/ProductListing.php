<?php

namespace App\Livewire;

use App\Models\Bike;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
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

    #[Url]
    public ?string $selectedManufacturer = null;

    #[Url]
    public ?string $selectedModel = null;

    #[Url]
    public ?string $selectedYear = null;

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

    public function updatedSelectedManufacturer(): void
    {
        $this->selectedModel = null;
        $this->selectedYear = null;
    }

    public function updatedSelectedModel(): void
    {
        $this->selectedYear = null;
    }

    public function navigateToBike(): mixed
    {
        if ($this->selectedManufacturer && $this->selectedModel && $this->selectedYear) {
            return redirect()->route('bikes.show', [
                'manufacturer' => strtolower($this->selectedManufacturer),
                'model' => $this->selectedModel,
                'year' => $this->selectedYear,
            ]);
        }

        return null;
    }

    public function clearFilters(): void
    {
        $this->search = null;
        $this->category = null;
        $this->type = null;
        $this->manufacturer = null;
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->selectedManufacturer = null;
        $this->selectedModel = null;
        $this->selectedYear = null;
        $this->sort = 'name_asc';
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        Log::info('ProductListing::addToCart called', ['productId' => $productId, 'productName' => $product->name]);

        $this->dispatch('addToCart', [
            'productId' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
        ]);
    }

    public function getFilteredProducts(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Product::query()->with(['category', 'bike'])->where('is_active', true);

        // Use Scout search if search term is provided
        if ($this->search) {
            $searchResults = Product::search($this->search)
                ->where('is_active', true);

            // Apply other filters to search results
            $searchResults = $searchResults
                ->when($this->category, fn ($query) => $query->where('category_id', $this->category))
                ->when($this->type, fn ($query) => $query->where('type', $this->type))
                ->when($this->manufacturer, fn ($query) => $query->where('manufacturer', $this->manufacturer))
                ->when($this->minPrice, fn ($query) => $query->where('price', '>=', $this->minPrice))
                ->when($this->maxPrice, fn ($query) => $query->where('price', '<=', $this->maxPrice));

            // Get the IDs from search results and load relationships
            $searchIds = $searchResults->get()->pluck('id');
            
            return Product::with(['category', 'bike'])
                ->whereIn('id', $searchIds)
                ->orderByRaw("CASE id " . $searchIds->map(fn($id, $index) => "WHEN {$id} THEN {$index}")->implode(' ') . " END")
                ->paginate(12);
        }

        // Apply filters for non-search queries
        $query = $query
            ->when($this->category, fn ($query) => $query->where('category_id', $this->category))
            ->when($this->type, fn ($query) => $query->where('type', $this->type))
            ->when($this->manufacturer, fn ($query) => $query->whereHas('bike', fn ($bikeQuery) => $bikeQuery->where('bikes.manufacturer', $this->manufacturer)))
            ->when($this->minPrice, fn ($query) => $query->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn ($query) => $query->where('price', '<=', $this->maxPrice))
            ->when($this->sort === 'name_asc', fn ($query) => $query->orderBy('name'))
            ->when($this->sort === 'name_desc', fn ($query) => $query->orderByDesc('name'))
            ->when($this->sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($this->sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($this->sort === 'manufacturer_asc', fn ($query) => $query->join('bikes', 'products.bike_id', '=', 'bikes.id')->orderBy('bikes.manufacturer'));

        return $query->paginate(12);
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
            ->whereHas('bike')
            ->with('bike')
            ->get()
            ->pluck('bike.manufacturer')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    public function getPriceRangeProperty()
    {
        return Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
    }

    public function getAvailableBikeManufacturersProperty()
    {
        return Bike::active()
            ->select('manufacturer')
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
    }

    public function getAvailableBikeModelsProperty()
    {
        if (! $this->selectedManufacturer) {
            return collect();
        }

        return Bike::active()
            ->select('model')
            ->where('manufacturer', $this->selectedManufacturer)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');
    }

    public function getAvailableBikeYearsProperty()
    {
        if (! $this->selectedManufacturer || ! $this->selectedModel) {
            return collect();
        }

        return Bike::active()
            ->select('year')
            ->where('manufacturer', $this->selectedManufacturer)
            ->whereRaw('LOWER(model) = ?', [strtolower(str_replace('-', ' ', $this->selectedModel))])
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    public function render(): \Illuminate\View\View
    {
        $filteredProducts = $this->getFilteredProducts();

        return view('livewire.product-listing', [
            'filteredProducts' => $filteredProducts,
            'categories' => $this->categories,
            'availableTypes' => $this->availableTypes,
            'availableManufacturers' => $this->availableManufacturers,
            'availableBikeManufacturers' => $this->availableBikeManufacturers,
            'availableBikeModels' => $this->availableBikeModels,
            'availableBikeYears' => $this->availableBikeYears,
        ]);
    }
}
