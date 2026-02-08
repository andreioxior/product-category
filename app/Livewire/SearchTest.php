<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class SearchTest extends Component
{
    public string $search = '';
    public array $results = [];

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            try {
                $this->results = Product::search($this->search)
                    ->options([
                        'query_by' => 'name,description,category_name,manufacturer,model',
                        'prefix' => true,
                        'per_page' => 5,
                    ])
                    ->where('is_active', true)
                    ->get()
                    ->toArray();
            } catch (\Exception $e) {
                $this->results = ['error' => $e->getMessage()];
            }
        } else {
            $this->results = [];
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.search-test');
    }
}