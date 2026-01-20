<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class CategoryList extends Component
{
    use WithPagination;

    public string $search = '';

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function getCategoriesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Category::query()
            ->where('name', 'ilike', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(20);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.category-list', [
            'categories' => $this->categories,
        ]);
    }
}
