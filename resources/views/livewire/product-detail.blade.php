<div>
    @push('meta')
        @if ($product)
            <meta name="description" content="{{ $metaDescription }}" />
            <meta name="keywords" content="{{ implode(', ', $metaKeywords) }}" />
            <meta property="og:title" content="{{ $title }}" />
            <meta property="og:description" content="{{ $metaDescription }}" />
            <meta property="og:image" content="{{ $product->image }}" />
            <meta property="og:type" content="product" />
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" content="{{ $title }}" />
            <meta name="twitter:description" content="{{ $metaDescription }}" />
            <meta name="twitter:image" content="{{ $product->image }}" />
        @endif
    @endpush
    
    <div>
        <livewire:cart />

        <flux:main>
            <div class="px-4 sm:px-6 lg:px-8 py-8">
                <x-breadcrumbs :items="$breadcrumbItems" />

                    @if ($product)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                            <div class="space-y-6">
                                @if ($product->image)
                                    <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden">
                                            <img
                                                src="{{ $product->image }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover"
                                                loading="lazy"
                                                decoding="async"
                                                width="600"
                                                height="600"
                                            />
                                    </div>
                                @else
                                    <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                        <svg class="w-32 h-32 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="flex items-center gap-3">
                                    @if ($product->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-sm font-medium text-green-900 dark:text-green-100">
                                            In Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-3 py-1 text-sm font-medium text-red-900 dark:text-red-100">
                                            Out of Stock
                                        </span>
                                    @endif
                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $product->stock_quantity }} available
                                    </flux:text>
                                </div>

                                <div class="space-y-4">
                                    <flux:button
                                        variant="primary"
                                        class="w-full"
                                        wire:click="addToCart"
                                    >
                                        Add to Cart
                                    </flux:button>
                                    <flux:button 
                                        variant="subtle" 
                                        class="w-full"
                                        wire:click="toggleWishlist"
                                    >
                                        @if($isInWishlist)
                                            <svg class="w-5 h-5 mr-2 text-red-500 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            Remove from Wishlist
                                        @else
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            Add to Wishlist
                                        @endif
                                    </flux:button>
                                </div>
                            </div>

                            <div class="space-y-6">
                                @if ($product->category)
                                    <div>
                                        <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 px-3 py-1 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                @endif

                                <flux:heading level="1">{{ $product->name }}</flux:heading>

                                <flux:text size="2xl" class="font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($product->price, 2) }}
                                </flux:text>

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                    <div>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Manufacturer</flux:text>
                                        <p class="font-medium">{{ $product->manufacturer }}</p>
                                    </div>
                                    @if($product->model)
                                    <div>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Model</flux:text>
                                        <p class="font-medium">{{ $product->model }}</p>
                                    </div>
                                    @endif
                                    @if($product->year)
                                    <div>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Year</flux:text>
                                        <p class="font-medium">{{ $product->year }}</p>
                                    </div>
                                    @endif
                                    <div>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Type</flux:text>
                                        <p class="font-medium">{{ $product->type }}</p>
                                    </div>
                                    @if ($product->sku)
                                        <div>
                                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">SKU</flux:text>
                                            <p class="font-medium">{{ $product->sku }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Stock</flux:text>
                                        <p class="font-medium">{{ $product->stock_quantity }} units</p>
                                    </div>
                                </div>

                                @if ($product->description)
                                    <div class="pt-6 border-t border-zinc-200 dark:border-zinc-700">
                                        <flux:heading level="3">Description</flux:heading>
                                        <flux:text class="mt-2 leading-relaxed">
                                            {{ $product->description }}
                                        </flux:text>
                                    </div>
                                @endif

                                <div class="pt-6 border-t border-zinc-200 dark:border-zinc-700">
                                    <flux:heading level="3">Additional Information</flux:heading>
                                    <div class="mt-4 space-y-3">
                                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                            <span class="text-zinc-600 dark:text-zinc-400">Category</span>
                                            <span class="font-medium">{{ $product->category?->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                            <span class="text-zinc-600 dark:text-zinc-400">Manufacturer</span>
                                            <span class="font-medium">{{ $product->manufacturer }}</span>
                                        </div>
                                        @if($product->model)
                                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                            <span class="text-zinc-600 dark:text-zinc-400">Model</span>
                                            <span class="font-medium">{{ $product->model }}</span>
                                        </div>
                                        @endif
                                        @if($product->year)
                                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                            <span class="text-zinc-600 dark:text-zinc-400">Year</span>
                                            <span class="font-medium">{{ $product->year }}</span>
                                        </div>
                                        @endif
                                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                            <span class="text-zinc-600 dark:text-zinc-400">Type</span>
                                            <span class="font-medium">{{ $product->type }}</span>
                                        </div>
                                        @if ($product->sku)
                                            <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                                <span class="text-zinc-600 dark:text-zinc-400">SKU</span>
                                                <span class="font-medium">{{ $product->sku }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-zinc-600 dark:text-zinc-400">Availability</span>
                                            <span class="font-medium {{ $product->is_active && $product->stock_quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $product->is_active && $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <flux:button variant="subtle" href="{{ route('products') }}">
                                        ← Back to Products
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto mb-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM9.172 9.172L9 9" />
                            </svg>
                            <flux:heading level="3">Product not found</flux:heading>
                            <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                                The product you're looking for doesn't exist.
                            </flux:text>
                            <div class="mt-6">
                                <flux:button href="{{ route('products') }}">
                                    Browse All Products
                                </flux:button>
                            </div>
                        </div>
                    @endif
                </div>
            </flux:main>
        </div>
    </div>
</div>
