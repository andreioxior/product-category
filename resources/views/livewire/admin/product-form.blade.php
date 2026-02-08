<form wire:submit.prevent="save" class="max-w-4xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <flux:heading level="2">
                    {{ $isEditing ? 'Edit Product' : 'Add Product' }}
                </flux:heading>
                <flux:button
                    variant="subtle"
                    href="{{ route('admin.products') }}"
                >
                    Cancel
                </flux:button>
            </div>

            <!-- Hidden manufacturer field for backward compatibility -->
<div style="display: none;">
    <flux:input wire:model="manufacturer" type="hidden" />
</div>

            <!-- Bike Selection/Creation Section -->
            <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 space-y-4">
                <flux:heading level="3">Bike Information</flux:heading>
                
                <div class="space-y-2">
                    <flux:label for="bike_selection">Bike Selection Mode</flux:label>
                    <flux:select id="bike_selection" wire:model.live="createNewBike">
                        <option value="0">Select existing bike</option>
                        <option value="1">Create new bike</option>
                    </flux:select>
                    @error('createNewBike')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                </div>

                @if($createNewBike === '0')
                    <div class="space-y-2">
                        <flux:label for="bike_id">Select Bike</flux:label>
                        <flux:select id="bike_id" wire:model="bike_id">
                            <option value="">Choose a bike...</option>
                            @foreach($availableBikes->sortByDesc('year')->sortBy('manufacturer')->sortBy('model') as $bike)
                                <option value="{{ $bike->id }}">
                                    {{ $bike->manufacturer }} {{ $bike->model }} {{ $bike->year }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('bike_id')
                            <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                        @enderror
                    </div>
                @endif

                @if($createNewBike === '1')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <flux:label for="bike_manufacturer">Manufacturer</flux:label>
                            <div class="relative">
                                <flux:input 
                                    id="bike_manufacturer" 
                                    wire:model.live="bike_manufacturer"
                                    type="text"
                                    placeholder="Type manufacturer..."
                                    list="manufacturers"
                                    class="w-full"
                                />
                                <datalist id="manufacturers">
                                    @foreach($availableManufacturers as $mfg)
                                        <option value="{{ $mfg }}">{{ $mfg }}</option>
                                    @endforeach
                                </datalist>
                                @if($manufacturerSuggestions)
                                    <div class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg max-h-40 overflow-y-auto">
                                        <div class="p-1">
                                            @foreach($manufacturerSuggestions as $suggestion)
                                                <button 
                                                    type="button" 
                                                    wire:click="$set('bike_manufacturer', '{{ $suggestion }}'); $refresh()"
                                                    class="w-full text-left px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded"
                                                >
                                                    {{ $suggestion }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('bike_manufacturer')
                                <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <flux:label for="bike_model">Model</flux:label>
                            <div class="relative">
                                <flux:input 
                                    id="bike_model" 
                                    wire:model.live="bike_model"
                                    type="text"
                                    placeholder="Type model name..."
                                    list="models"
                                    class="w-full"
                                />
                                <datalist id="models">
                                    @if($bike_manufacturer)
                                        @foreach($availableModels as $model)
                                            <option value="{{ $model }}">{{ $model }}</option>
                                        @endforeach
                                    @endif
                                </datalist>
                                @if($showModelSuggestions && $modelSuggestions)
                                    <div class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg max-h-40 overflow-y-auto">
                                        <div class="p-1">
                                            @foreach($modelSuggestions as $suggestion)
                                                <button 
                                                    type="button" 
                                                    wire:click="$set('bike_model', '{{ $suggestion }}'); $refresh()"
                                                    class="w-full text-left px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded"
                                                >
                                                    {{ $suggestion }}
                                                </button>
                                            @endforeach
                                            @if($availableModels->count() === 0 && $bike_model)
                                                <div class="px-3 py-2 text-sm text-zinc-500 dark:text-zinc-400">
                                                    No matches. Create new model?
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('bike_model')
                                <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <flux:label for="bike_year">Year</flux:label>
                            <flux:input 
                                id="bike_year" 
                                wire:model.live="bike_year"
                                type="number"
                                min="1950" 
                                max="{{ date('Y') }}"
                                placeholder="Year..."
                                class="w-full"
                            />
                            @error('bike_year')
                                <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                            @enderror
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <flux:label for="category_id">Category</flux:label>
                        <flux:select id="category_id" wire:model="category_id">
                            <option value="">Select a category</option>
                            @foreach ($this->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </flux:select>
                    @error('category_id')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                    </div>

                    <div class="space-y-2">
                        <flux:label for="sku">SKU (optional)</flux:label>
                        <flux:input
                            id="sku"
                            wire:model="sku"
                            type="text"
                            placeholder="Product SKU"
                        ></flux:input>
                    @error('sku')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <flux:label for="product_name">Name</flux:label>
                    <flux:input
                        id="product_name"
                        wire:model="name"
                        type="text"
                        placeholder="Product name"
                    ></flux:input>
                @error('name')
                    <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                @enderror
                </div>

                <div class="space-y-2">
                    <flux:label for="description">Description (optional)</flux:label>
                    <flux:textarea
                        id="description"
                        wire:model="description"
                        placeholder="Product description"
                        rows="3"
                    ></flux:textarea>
                </div>

                <div class="space-y-2">
                    <flux:label for="image_url">Image URL (optional)</flux:label>
                    <flux:input
                        id="image_url"
                        wire:model="image"
                        type="url"
                        placeholder="https://example.com/image.jpg"
                    ></flux:input>
                    @error('image')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                    @if ($image)
                        <div class="mt-2">
                            <img src="{{ $image }}" alt="Product image preview" class="h-32 w-auto rounded-md border border-zinc-200 dark:border-zinc-700" />
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <flux:label for="product_type">Type</flux:label>
                    <flux:input
                        id="product_type"
                        wire:model="type"
                        type="text"
                        placeholder="Product type"
                    ></flux:input>
                @error('type')
                    <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <flux:label for="price">Price</flux:label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">$</span>
                            <flux:input
                                id="price"
                                wire:model="price"
                                type="number"
                                step="0.01"
                                min="0"
                                class="pl-8"
                            ></flux:input>
                        </div>
                        @error('price')
                            <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <flux:label for="stock_quantity">Stock Quantity</flux:label>
                        <flux:input
                            id="stock_quantity"
                            wire:model="stock_quantity"
                            type="number"
                            min="0"
                            placeholder="Stock quantity"
                        ></flux:input>
                        @error('stock_quantity')
                            <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:checkbox id="is_active" wire:model="is_active" />
                    <flux:label for="is_active">Active</flux:label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button
                        variant="subtle"
                        href="{{ route('admin.products') }}"
                    >
                        Cancel
                    </flux:button>
                    <flux:button type="submit">
                        {{ $isEditing ? 'Update' : 'Create' }} Product
                    </flux:button>
                </div>
            </div>
        </form>
