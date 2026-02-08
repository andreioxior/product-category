<div>
    <livewire:cart />

    <flux:spacer size="4" />

    <flux:main>
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-4 mb-6">
                    @php
                        $breadcrumbs = [['label' => $manufacturerBike->manufacturer]];
                    @endphp
                    <x-breadcrumbs :items="$breadcrumbs" />
                </div>

                <div class="mb-8">
                    <flux:heading level="2">
                        {{ $manufacturerBike->manufacturer }} Bikes
                    </flux:heading>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">
                        {{ $filteredProducts->total() }} products available
                    </flux:text>
                </div>

                <div class="mb-6">
                    <flux:button href="{{ route('products') }}" variant="subtle">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to All Products
                    </flux:button>
                </div>

                @isset($availableModels)
                <div class="mb-8 bg-zinc-50 dark:bg-zinc-800 rounded-lg p-6">
                    <flux:heading level="3">Available Models</flux:heading>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 mt-4">
                        @foreach ($availableModels as $model)
                            <flux:button :href="route('bikes.model', ['manufacturer' => strtolower($manufacturerBike->manufacturer), 'model' => str_replace(' ', '-', strtolower($model))])" variant="subtle" size="sm" class="w-full">
                                {{ $model }}
                            </flux:button>
                        @endforeach
                    </div>
                </div>
                @endisset

                <div class="flex items-center gap-4 mb-6">
                    <div class="relative" x-data="{ open: false }">
                        <flux:button variant="subtle" @click="open = !open">
                            Sort by: {{ $sortOptions[$sort] }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </flux:button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute z-50 mt-2 w-56 rounded-md bg-white dark:bg-zinc-800 shadow-lg border border-zinc-200 dark:border-zinc-700 py-1"
                        >
                            @foreach ($sortOptions as $key => $label)
                                <button
                                    wire:click="$set('sort', @json($key))"
                                    class="w-full text-left px-3 py-2 text-sm {{ $sort === $key ? 'bg-zinc-100 dark:bg-zinc-700' : 'hover:bg-zinc-50 dark:hover:bg-zinc-700' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if ($filteredProducts->count() === 0)
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM9.172 9.172L9 9" />
                        </svg>
                        <flux:heading level="3">No products found</flux:heading>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 mb-4">
                            There are no products available for this manufacturer.
                        </flux:text>
                        <flux:button href="{{ route('products') }}">
                            View All Products
                        </flux:button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($filteredProducts as $product)
                            <div class="group bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                <a href="{{ route('products.show', $product) }}" class="block">
                                    <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                                        @if ($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                                        @else
                                            <svg class="w-20 h-20 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <div>
                                            <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 px-2 py-1 text-xs font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $product->category->name }}
                                            </span>
                                        </div>
                                        <h3 class="font-semibold text-base truncate">{{ $product->name }}</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">
                                            {{ $product->description ?? 'No description available' }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            <div class="space-y-1">
                                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    {{ $product->bike->manufacturer }} - {{ $product->bike->model }}
                                                </div>
                                                <div class="font-semibold text-green-600 dark:text-green-400">
                                                    ${{ number_format($product->price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="p-4 pt-0">
                                    <button
                                        wire:click="addToCart({{ $product->id }})"
                                        type="button"
                                        class="w-full opacity-0 group-hover:opacity-100 transition-opacity inline-flex items-center justify-center rounded-md bg-zinc-100 dark:bg-zinc-800 px-3 py-1.5 text-sm font-medium text-zinc-900 dark:text-zinc-100 hover:bg-zinc-200 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 0 2 2 0 000-4zm-8 2a2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($filteredProducts->hasPages())
                        <div class="flex items-center justify-center mt-8">
                            {!! $filteredProducts->appends(['sort' => $sort])->links() !!}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </flux:main>
</div>
