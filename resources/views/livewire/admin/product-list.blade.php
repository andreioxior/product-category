<div class="space-y-6 px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
            <flux:heading level="2">Products</flux:heading>
            <flux:button href="{{ route('admin.products.create') }}">
                Add Product
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 space-y-4">
                <flux:input
                    wire:model.live="search"
                    placeholder="Search products..."
                    type="text"
                />
                <flux:select
                    wire:model.live="category"
                    placeholder="All Categories"
                >
                    <option value="">All Categories</option>
                    @foreach ($this->categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            @if ($products->count() > 0)
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($products as $product)
                        <div class="flex items-center gap-4 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            @if ($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-md object-cover" loading="lazy" />
                            @else
                                <div class="w-16 h-16 rounded-md bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-base truncate">{{ $product->name }}</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                    {{ $product->manufacturer }}
                                    @if($product->model) · {{ $product->model }} @endif
                                    @if($product->year) · {{ $product->year }} @endif
                                    · {{ $product->type }} · ${{ number_format($product->price, 2) }}
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                    @if ($product->category)
                                        <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-700 px-2 py-1 text-xs font-medium">
                                            {{ $product->category->name }}
                                        </span>
                                    @endif
                                    @if (!$product->is_active)
                                        <span class="inline-flex items-center rounded-md bg-red-100 dark:bg-red-900 px-2 py-1 text-xs font-medium text-red-900 dark:text-red-100">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    variant="subtle"
                                    size="sm"
                                    href="{{ route('admin.products.edit', [$product]) }}"
                                >
                                    Edit
                                </flux:button>
                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    wire:click="delete({{ $product->id }})"
                                    wire:confirm="Are you sure you want to delete this product?"
                                >
                                    Delete
                                </flux:button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $products->links() }}
                </div>
            @else
                <div class="p-12 text-center text-zinc-500 dark:text-zinc-400">
                    No products found.
                </div>
            @endif
        </div>
    </div>
