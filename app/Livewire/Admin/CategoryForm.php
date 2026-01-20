<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class CategoryForm extends Component
{
    public ?Category $category = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = '';

    public bool $isEditing = false;

    public function mount(?Category $category = null): void
    {
        if ($category) {
            $this->category = $category;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->isEditing = true;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->category) {
            $this->category->update([
                'name' => $this->name,
                'description' => $this->description,
                'slug' => \Illuminate\Support\Str::slug($this->name),
            ]);
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
                'slug' => \Illuminate\Support\Str::slug($this->name),
                'is_active' => true,
            ]);
        }

        $this->redirectRoute('admin.categories');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.category-form');
    }
}
