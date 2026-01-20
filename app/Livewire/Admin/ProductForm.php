<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ProductForm extends Component
{
    public ?Product $product = null;

    #[Validate('required|exists:categories,id')]
    public ?string $category_id = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('nullable|url')]
    public ?string $image = null;

    #[Validate('required|string|max:255')]
    public string $type = '';

    #[Validate('required|string|max:255')]
    public string $manufacturer = '';

    #[Validate('required|numeric|min:0')]
    public float $price = 0;

    #[Validate('nullable|string|max:255')]
    public ?string $sku = null;

    #[Validate('required|integer|min:0')]
    public int $stock_quantity = 0;

    #[Validate('boolean')]
    public bool $is_active = true;

    public bool $isEditing = false;

    public function mount(?Product $product = null): void
    {
        if ($product) {
            $this->product = $product;
            $this->category_id = (string) $product->category_id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->image = $product->image;
            $this->type = $product->type;
            $this->manufacturer = $product->manufacturer;
            $this->price = (float) $product->price;
            $this->sku = $product->sku;
            $this->stock_quantity = $product->stock_quantity;
            $this->is_active = $product->is_active;
            $this->isEditing = true;
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'type' => $this->type,
            'manufacturer' => $this->manufacturer,
            'price' => $this->price,
            'sku' => $this->sku,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
        ];

        if ($this->product) {
            $this->product->update($data);
        } else {
            Product::create($data);
        }

        $this->redirect(route('admin.products'), navigate: true);
    }

    public function getCategoriesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderBy('name')->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.product-form', [
            'categories' => $this->categories,
        ]);
    }
}
