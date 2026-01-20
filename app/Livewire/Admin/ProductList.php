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
            ->with('category')
            ->where('name', 'ilike', "%{$this->search}%")
            ->when($this->category, fn ($query) => $query->where('category_id', $this->category))
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.admin.product-list', [
            'products' => $products,
        ]);
    }
}
