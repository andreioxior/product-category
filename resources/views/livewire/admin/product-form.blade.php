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
                            <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
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
                            <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
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
                        <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
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
                        <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
                    @enderror
                    @if ($image)
                        <div class="mt-2">
                            <img src="{{ $image }}" alt="Product image preview" class="h-32 w-auto rounded-md border border-zinc-200 dark:border-zinc-700" />
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <flux:label for="product_type">Type</flux:label>
                        <flux:input
                            id="product_type"
                            wire:model="type"
                            type="text"
                            placeholder="Product type"
                        ></flux:input>
                        @error('type')
                            <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <flux:label for="manufacturer">Manufacturer</flux:label>
                        <flux:input
                            id="manufacturer"
                            wire:model="manufacturer"
                            type="text"
                            placeholder="Manufacturer name"
                        ></flux:input>
                        @error('manufacturer')
                            <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
                        @enderror
                    </div>
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
                            <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
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
                            <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
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
