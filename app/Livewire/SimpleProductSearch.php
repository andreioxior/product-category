<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\ProductSearchService;
use Livewire\Component;

class SimpleProductSearch extends Component
{
    public string $search = '';
    public array $suggestions = [];
    public string $searchType = 'all';
    public array $facets = [];
    public bool $loading = false;

    protected ProductSearchService $searchService;

    public function boot(ProductSearchService $searchService): void
    {
        $this->searchService = $searchService;
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            $this->loading = true;
            $this->performSearch();
            $this->loading = false;
        } else {
            $this->suggestions = [];
            $this->facets = [];
        }
    }

    public function updatedSearchType(): void
    {
        if (strlen($this->search) >= 2) {
            $this->loading = true;
            $this->performSearch();
            $this->loading = false;
        }
    }

    private function performSearch(): void
    {
        $searchData = $this->searchService->enhancedAutocomplete($this->search, [
            'search_type' => $this->searchType,
            'limit' => 8
        ]);

        $this->suggestions = $searchData['results'];
        $this->facets = $searchData['facets'];
    }

    public function selectProduct(string $productName): void
    {
        $this->redirect(route('products', ['search' => $productName]));
    }

    public function setSearchType(string $type): void
    {
        $this->searchType = $type;
    }

    public function getSearchTypeOptions(): array
    {
        return [
            'all' => ['label' => 'All', 'icon' => 'magnifying-glass'],
            'name' => ['label' => 'Product Name', 'icon' => 'tag'],
            'manufacturer' => ['label' => 'Manufacturer', 'icon' => 'building'],
            'model' => ['label' => 'Model', 'icon' => 'gear'],
            'year' => ['label' => 'Year', 'icon' => 'calendar']
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.simple-product-search', [
            'searchTypeOptions' => $this->getSearchTypeOptions()
        ]);
    }
}