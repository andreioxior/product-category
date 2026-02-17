<?php

namespace App\Livewire\Admin;

use App\Models\Bike;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
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

    public string $manufacturer = '';

    public ?int $bike_id = null;

    public string $bike_manufacturer = '';

    public string $bike_model = '';

    public ?int $bike_year = null;

    #[Validate('required|numeric|min:0')]
    public float $price = 0;

    #[Validate('nullable|string|max:255')]
    public ?string $sku = null;

    #[Validate('required|integer|min:0')]
    public int $stock_quantity = 0;

    #[Validate('boolean')]
    public bool $is_active = true;

    public bool $isEditing = false;

    public string $createNewBike = '1';

    public Collection $availableBikes;

    public Collection $availableManufacturers;

    public Collection $availableModels;

    public Collection $availableYears;

    public array $manufacturerSuggestions = [];

    public array $modelSuggestions = [];

    public bool $showModelSuggestions = false;

    public function mount(?Product $product = null): void
    {
        // Set default year to current year
        $this->bike_year = (int) date('Y');

        // Initialize collections
        $this->availableBikes = Bike::active()->get();
        $this->availableManufacturers = Bike::active()->distinct()->orderBy('manufacturer')->pluck('manufacturer');
        $this->availableModels = collect([]);
        $this->availableYears = collect([]);

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
            $this->bike_id = $product->bike_id ? (int) $product->bike_id : null;

            if ($product->bike) {
                $this->bike_manufacturer = $product->bike->manufacturer;
                $this->bike_model = $product->bike->model;
                $this->bike_year = (int) $product->bike->year;
            }

            $this->isEditing = true;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->createNewBike === '1') {
            // Create new bike with active status
            $bike = Bike::firstOrCreate([
                'manufacturer' => $this->bike_manufacturer,
                'model' => $this->bike_model,
                'year' => $this->bike_year,
            ], [
                'is_active' => true,
            ]);
            $this->bike_id = $bike->id;

            // Update hidden manufacturer field for backward compatibility
            $this->manufacturer = $bike->manufacturer;
        } else {
            // Set manufacturer from selected bike for backward compatibility
            $bike = Bike::find((int) $this->bike_id);
            $this->manufacturer = $bike?->manufacturer;
        }

        if (! $this->bike_id) {
            throw new \Exception('Bike selection is required.');
        }

        $data = [
            'category_id' => $this->category_id,
            'bike_id' => $this->bike_id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'type' => $this->type,
            'price' => $this->price,
            'sku' => $this->sku,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'manufacturer' => $this->manufacturer, // Hidden field for compatibility
        ];

        if ($this->product) {
            $this->product->update($data);
        } else {
            Product::create($data);
        }

        $this->redirect(route('admin.products'), navigate: true);
    }

    public function updatedCreateNewBike(string $value): void
    {
        if ($value === '1') {
            // Switching to create new bike mode
            $this->bike_id = null;
        } else {
            // Switching to existing bike mode
            $this->bike_manufacturer = '';
            $this->bike_model = '';
            $this->bike_year = null;
            $this->availableModels = collect([]);
            $this->availableYears = collect([]);
        }
    }

    public function updatedBikeManufacturer($value): void
    {
        if ($value) {
            // Get all models for the selected manufacturer
            $this->availableModels = Bike::where('manufacturer', $value)
                ->where('is_active', true)
                ->distinct()
                ->orderBy('model')
                ->pluck('model')
                ->values();

            // Prepare manufacturer suggestions (with fuzzy matching)
            $this->manufacturerSuggestions = Bike::where('manufacturer', 'like', "%{$value}%")
                ->where('is_active', true)
                ->distinct()
                ->orderBy('manufacturer')
                ->pluck('manufacturer')
                ->take(5)
                ->toArray();

            $this->showModelSuggestions = ! empty($value);
        } else {
            $this->availableModels = collect([]);
            $this->availableYears = collect([]);
            $this->manufacturerSuggestions = [];
            $this->showModelSuggestions = false;
        }

        $this->bike_model = '';
        $this->bike_year = (int) date('Y');
    }

    public function updatedBikeModel($value): void
    {
        if ($value && $this->bike_manufacturer) {
            // Get years for the selected manufacturer and model (with fuzzy matching for model)
            $this->availableYears = Bike::where('manufacturer', $this->bike_manufacturer)
                ->where('model', 'like', "%{$value}%")
                ->where('is_active', true)
                ->orderByDesc('year')
                ->pluck('year')
                ->values();

            // Prepare model suggestions (with fuzzy matching)
            if ($value && $this->bike_manufacturer) {
                $this->modelSuggestions = Bike::where('manufacturer', $this->bike_manufacturer)
                    ->where('model', 'like', "%{$value}%")
                    ->where('is_active', true)
                    ->orderBy('model')
                    ->pluck('model')
                    ->take(5)
                    ->toArray();
                $this->showModelSuggestions = true;
            } else {
                $this->modelSuggestions = [];
                $this->showModelSuggestions = false;
            }
        } else {
            $this->availableYears = collect([]);
            $this->modelSuggestions = [];
            $this->showModelSuggestions = false;
        }

        $this->bike_year = (int) date('Y');
    }

    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->createNewBike === '1') {
            $bikeManufacturer = $this->bike_manufacturer ?? '';
            $rules['bike_manufacturer'] = 'required|string|max:255';

            // Skip unique validation for bike model when editing and keeping the same bike
            if ($this->isEditing && $this->product && $this->product->bike &&
                $this->product->bike->manufacturer === $bikeManufacturer &&
                $this->product->bike->model === $this->bike_model) {
                $rules['bike_model'] = 'required|string|max:255';
            } else {
                $rules['bike_model'] = 'required|string|max:255|unique:bikes,model,NULL,id,manufacturer,'.$bikeManufacturer;
            }

            $rules['bike_year'] = 'required|integer|min:1950|max:'.(int) date('Y');
        } else {
            $rules['bike_id'] = 'required|exists:bikes,id';
            $rules['bike_year'] = 'nullable|integer|min:1950|max:'.(int) date('Y');
        }

        return $rules;
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
