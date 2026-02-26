<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Services\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class ProductListingCached extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?int $selectedCategoryId = null;

    #[Url]
    public string $sortField = 'name_asc';

    public bool $loading = false;

    #[Url]
    public ?string $selectedManufacturer = null;

    #[Url]
    public ?string $selectedModel = null;

    #[Url]
    public ?string $selectedYear = null;

    protected $queryString = [
        'search',
        'selectedCategoryId' => ['as' => 'category'],
        'sortField' => ['as' => 'sort'],
        'selectedManufacturer',
        'selectedModel',
        'selectedYear',
    ];

    public function mount(): void
    {
        $this->resetPage();
    }

    public function getPageTitleProperty(): string
    {
        $parts = [];

        if ($this->search) {
            $parts[] = 'Search: '.$this->search;
        }

        if ($this->selectedCategory) {
            $parts[] = $this->selectedCategory->name;
        }

        if ($this->selectedManufacturer) {
            $parts[] = $this->selectedManufacturer;
        }

        if ($this->selectedModel) {
            $parts[] = $this->selectedModel;
        }

        if ($this->selectedYear) {
            $parts[] = $this->selectedYear;
        }

        if (empty($parts)) {
            return config('app.name', 'Bike Shop').' - Professional Bike Parts & Accessories';
        }

        return implode(' | ', $parts).' | '.config('app.name', 'Bike Shop');
    }

    public function getFilteredProductsProperty(): LengthAwarePaginator
    {
        $this->loading = true;

        $filterData = [
            'search' => $this->search,
            'category' => $this->selectedCategoryId,
            'sort' => $this->sortField,
            'manufacturer' => $this->selectedManufacturer,
            'model' => $this->selectedModel,
            'year' => $this->selectedYear,
            'page' => $this->getPage(),
        ];
        $cacheKey = 'products_page_'.md5(json_encode($filterData));

        $result = Cache::store('products')->remember($cacheKey, now()->addMinutes(30), function () {
            $query = Product::query()
                ->with(['category', 'bike'])
                ->where('is_active', true);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('products.name', 'ilike', '%'.$this->search.'%')
                        ->orWhere('products.description', 'ilike', '%'.$this->search.'%')
                        ->orWhere('products.type', 'ilike', '%'.$this->search.'%')
                        ->orWhereHas('bike', function ($subQuery) {
                            $subQuery->where('manufacturer', 'ilike', '%'.$this->search.'%')
                                ->orWhere('model', 'ilike', '%'.$this->search.'%')
                                ->orWhere('year', 'ilike', '%'.$this->search.'%');
                        })
                        ->orWhereHas('category', function ($subQuery) {
                            $subQuery->where('name', 'ilike', '%'.$this->search.'%');
                        });
                });
            }

            if ($this->selectedCategoryId) {
                $query->where('category_id', $this->selectedCategoryId);
            }

            match ($this->sortField) {
                'name_asc' => $query->orderBy('name'),
                'name_desc' => $query->orderByDesc('name'),
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest' => $query->orderByDesc('created_at'),
                default => $query->orderBy('name'),
            };

            return $query->paginate(12);
        });

        $this->loading = false;

        return $result;
    }

    public function getCategoriesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return CacheService::categories();
    }

    public function getSelectedCategoryProperty(): ?Category
    {
        if (! $this->selectedCategoryId) {
            return null;
        }

        return $this->categories->firstWhere('id', $this->selectedCategoryId);
    }

    public function getTotalProductsCountProperty(): int
    {
        return CacheService::totalProductsCount();
    }

    public function getAvailableBikeManufacturersProperty(): array
    {
        return CacheService::bikeManufacturers();
    }

    public function getAvailableBikeModelsProperty(): array
    {
        if (! $this->selectedManufacturer) {
            return [];
        }

        return CacheService::bikeModels($this->selectedManufacturer);
    }

    public function getAvailableBikeYearsProperty(): array
    {
        if (! $this->selectedManufacturer || ! $this->selectedModel) {
            return [];
        }

        return CacheService::bikeYears($this->selectedManufacturer, $this->selectedModel);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedSortField(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedManufacturer(): void
    {
        $this->selectedModel = null;
        $this->selectedYear = null;
        $this->resetPage();
    }

    public function updatedSelectedModel(): void
    {
        $this->selectedYear = null;
        $this->resetPage();
    }

    public function updatedSelectedYear(): void
    {
        $this->resetPage();
    }

    public function navigateToManufacturer(): void
    {
        $this->redirectRoute('bikes.manufacturer', ['manufacturer' => $this->selectedManufacturer]);
    }

    public function navigateToModel(): void
    {
        if ($this->selectedManufacturer && $this->selectedModel) {
            $this->redirectRoute('bikes.model', [
                'manufacturer' => $this->selectedManufacturer,
                'model' => $this->selectedModel,
            ]);
        }
    }

    public function navigateToBike(): void
    {
        if ($this->selectedManufacturer && $this->selectedModel && $this->selectedYear) {
            $this->redirectRoute('bikes.show', [
                'manufacturer' => $this->selectedManufacturer,
                'model' => $this->selectedModel,
                'year' => $this->selectedYear,
            ]);
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategoryId = null;
        $this->sortField = 'name_asc';
        $this->selectedManufacturer = null;
        $this->selectedModel = null;
        $this->selectedYear = null;
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

    public function render(): \Illuminate\View\View
    {
        return view('livewire.product-listing-cached', [
            'pageTitle' => $this->pageTitle,
            'products' => $this->filteredProducts,
            'categories' => $this->categories,
            'selectedCategory' => $this->selectedCategory,
            'totalProductsCount' => $this->totalProductsCount,
            'availableBikeManufacturers' => $this->availableBikeManufacturers,
            'availableBikeModels' => $this->availableBikeModels,
            'availableBikeYears' => $this->availableBikeYears,
        ]);
    }
}
