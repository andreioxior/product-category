<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ProductList extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $category = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function getCategoriesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderBy('name')->get();
    }

    public function render(): \Illuminate\View\View
    {
        $products = Product::query()
            ->with(['category', 'bike'])
            ->when($this->search, function ($query) {
                $query->where('name', 'ilike', "%{$this->search}%")
                    ->orWhereHas('bike', function ($bikeQuery) {
                        $bikeQuery->where('manufacturer', 'ilike', "%{$this->search}%")
                            ->orWhere('model', 'ilike', "%{$this->search}%");
                    });
            })
            ->when($this->category, fn ($query) => $query->where('category_id', $this->category))
            ->orderByDesc('created_at') // Sort by latest first
            ->paginate(20);

        return view('livewire.admin.product-list', [
            'products' => $products,
        ]);
    }
}
