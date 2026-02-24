<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ProductSearch extends Component
{
    public string $search = '';

    public string $selectedSuggestion = '';

    public bool $showSuggestions = false;

    public int $maxSuggestions = 8;

    public function updatedSearch(): void
    {
        $this->showSuggestions = strlen($this->search) >= 2;

        if (app()->isLocal() && strlen($this->search) >= 2) {
            Log::info('ProductSearch: Searching for', ['query' => $this->search]);
        }
    }

    public function selectSuggestion(string $suggestion)
    {
        $this->search = $suggestion;
        $this->showSuggestions = false;

        return $this->redirect(route('products', ['search' => $suggestion]));
    }

    public function performSearch()
    {
        if (empty($this->search)) {
            return;
        }

        $this->showSuggestions = false;

        return $this->redirect(route('products', ['search' => $this->search]));
    }

    public function closeSuggestions(): void
    {
        $this->showSuggestions = false;
    }

    public function getSuggestionsProperty(): array
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        $debug = app()->isLocal();

        try {
            if ($debug) {
                Log::info('ProductSearch: Searching for "'.$this->search.'"');
            }

            $results = Product::search($this->search)
                ->where('is_active', true)
                ->take($this->maxSuggestions)
                ->get();

            if ($debug) {
                Log::info('ProductSearch: Found '.$results->count().' results');
            }

            $suggestions = [];
            foreach ($results as $product) {
                $suggestions[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category_name,
                    'manufacturer' => $product->manufacturer,
                    'model' => $product->model,
                    'year' => $product->year,
                    'display_text' => $this->buildDisplayText($product),
                ];
            }

            if ($debug) {
                Log::info('ProductSearch: Returning '.count($suggestions).' suggestions');
            }

            return $suggestions;
        } catch (\Exception $e) {
            if ($debug) {
                Log::error('ProductSearch suggestions error: '.$e->getMessage());
            }

            return [];
        }
    }

    private function buildDisplayText($product): string
    {
        $parts = array_filter([$product->name, $product->manufacturer, $product->model, $product->year]);

        return implode(' - ', $parts);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.product-search');
    }
}
