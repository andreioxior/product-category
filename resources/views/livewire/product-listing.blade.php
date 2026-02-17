        <div>
            <livewire:cart />

            <flux:spacer size="4" />

            <flux:main>
                <div class="px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:gap-8">
                    <aside class="hidden lg:block w-64 shrink-0">
                    <div class="sticky top-4 space-y-6">
                        <flux:heading level="2">Browse by Bike</flux:heading>
                        
                        <div class="space-y-4">
                            <flux:heading level="3">Select Your Bike</flux:heading>
                            
                            <div class="space-y-4">
                                <div>
                                    <flux:heading level="4" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Manufacturer</flux:heading>
                                    <div class="relative">
                                        <select
                                            wire:model.live="selectedManufacturer"
                                            wire:loading.attr="disabled"
                                            class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                        >
                                            <option value="">Choose Manufacturer</option>
                                            @foreach ($availableBikeManufacturers as $m)
                                                <option value="{{ $m }}">{{ $m }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 pointer-events-none opacity-0" wire:loading wire:target="selectedManufacturer">
                                            <svg class="shrink-0 w-4 h-4 animate-spin text-zinc-600 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <flux:heading level="4" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Model</flux:heading>
                                    <div class="relative">
                                        <select
                                            wire:model.live="selectedModel"
                                            wire:loading.attr="disabled"
                                            class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400 disabled:opacity-50"
                                            @if(!$selectedManufacturer) disabled @endif
                                        >
                                            <option value="">Choose Model</option>
                                            @if($selectedManufacturer)
                                                @foreach ($availableBikeModels as $model)
                                                    <option value="{{ $model }}">{{ $model }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 pointer-events-none opacity-0" wire:loading wire:target="selectedModel">
                                            <svg class="shrink-0 w-4 h-4 animate-spin text-zinc-600 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <flux:heading level="4" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Year</flux:heading>
                                    <div class="relative">
                                        <select
                                            wire:model.live="selectedYear"
                                            wire:loading.attr="disabled"
                                            class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400 disabled:opacity-50"
                                            @if(!$selectedModel) disabled @endif
                                        >
                                            <option value="">Choose Year</option>
                                            @if($selectedModel)
                                                @foreach ($availableBikeYears as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 pointer-events-none opacity-0" wire:loading wire:target="selectedYear">
                                            <svg class="shrink-0 w-4 h-4 animate-spin text-zinc-600 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progressive Navigation Buttons -->
                                <div class="space-y-2 pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                    
                                    <!-- Manufacturer Button -->
                                    @if($selectedManufacturer)
                                        <div class="relative">
                                            <flux:button 
                                                variant="primary"
                                                wire:click="navigateToManufacturer"
                                                class="w-full flex items-center justify-center"
                                            >
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                                <span>View All {{ $selectedManufacturer }} Products</span>
                                            </flux:button>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToManufacturer">
                                                <svg class="shrink-0 w-4 h-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Model Button -->
                                    @if($selectedManufacturer && $selectedModel)
                                        <div class="relative">
                                            <flux:button 
                                                variant="primary"
                                                wire:click="navigateToModel"
                                                class="w-full flex items-center justify-center"
                                            >
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                                <span>View All {{ $selectedManufacturer }} {{ $selectedModel }} Products</span>
                                            </flux:button>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToModel">
                                                <svg class="shrink-0 w-4 h-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Year Button -->
                                    @if($selectedManufacturer && $selectedModel && $selectedYear)
                                        <div class="relative">
                                            <flux:button 
                                                variant="primary"
                                                wire:click="navigateToBike"
                                                class="w-full flex items-center justify-center"
                                            >
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                                <span>View {{ $selectedManufacturer }} {{ $selectedModel }} {{ $selectedYear }} Products</span>
                                            </flux:button>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToBike">
                                                <svg class="shrink-0 w-4 h-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                            <flux:heading level="2">Product Filters</flux:heading>

                        <div class="space-y-2">
                            <flux:heading level="3">Search</flux:heading>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-[17px] h-[17px] text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    wire:model.debounce.300ms="search"
                                    type="text"
                                    placeholder="Search products..."
                                    class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-10 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                />
                            </div>
                        </div>

                        @isset($categories)
                        @isset($availableTypes)
                        @isset($availableManufacturers)
                        <div class="space-y-2">
                            <flux:heading level="3">Category</flux:heading>
                            <select
                                wire:model.lazy="category"
                                class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                            >
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <flux:heading level="3">Type</flux:heading>
                            <select
                                wire:model.lazy="type"
                                class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                            >
                                <option value="">All Types</option>
                                @foreach ($availableTypes as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <flux:heading level="3">Manufacturer</flux:heading>
                            <select
                                wire:model.lazy="manufacturer"
                                class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                            >
                                <option value="">All Manufacturers</option>
                                @foreach ($availableManufacturers as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endisset
                        @endisset
                        @endisset

                        <div class="space-y-2">
                            <flux:heading level="3">Price Range</flux:heading>
                            <div class="space-y-3">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">$</span>
                                    <input
                                        wire:model.lazy="minPrice"
                                        type="number"
                                        placeholder="Min price"
                                        class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-8 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                    />
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">$</span>
                                    <input
                                        wire:model.lazy="maxPrice"
                                        type="number"
                                        placeholder="Max price"
                                        class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-8 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                    />
                                </div>
                            </div>
                        </div>

                        @if ($search || $category || $type || $manufacturer || $minPrice || $maxPrice)
                            <flux:button
                                variant="subtle"
                                wire:click="clearFilters"
                                class="w-full"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear Filters
                            </flux:button>
                        @endif
                    </div>
                    </aside>

                    <div class="flex-1 space-y-6">
                        @isset($filteredProducts)
                        <div class="flex items-center justify-between">
                            <flux:heading level="2">All Products</flux:heading>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $filteredProducts->total() }} products
                            </span>
                        </div>

                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="relative" x-data="{ open: false }">
                                <flux:button
                                    variant="subtle"
                                    @click="open = !open"
                                >
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
                                            wire:click="sort = {{ $key }}"
                                            class="w-full text-left px-3 py-2 text-sm {{ $sort === $key ? 'bg-zinc-100 dark:bg-zinc-700' : 'hover:bg-zinc-50 dark:hover:bg-zinc-700' }}"
                                        >
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <flux:button
                                variant="subtle"
                                class="lg:hidden"
                                @click="$dispatch('toggle-mobile-filters')"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4 0 2 2 0 000-4zm0 7v-1m0 1a2 2 0 100 4 0 2 2 0 000-4zm0 7v-1m0 1a2 2 0 100 4 0 2 2 0 000-4zm0-13v1m0-1a2 2 0 100 4 0 2 2 0 010 4zm0-13v1m0-1a2 2 0 100 4 0 2 2 0 010 4zm0 13v1m0-1a2 2 0 110-4 0 2 2 0 010 4zm0 13v1m0-1a2 2 0 110-4 0 2 2 0 010 4z" />
                                </svg>
                                Filters
                            </flux:button>
                        </div>

                        @if ($filteredProducts->count() === 0)
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto mb-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM9.172 9.172L9 9" />
                                </svg>
                                <flux:heading level="3">No products found</flux:heading>
                                <flux:text class="text-zinc-500 dark:text-zinc-400">
                                    Try adjusting your filters or search terms.
                                </flux:text>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($filteredProducts as $product)
                                    <div class="group bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                        <a href="{{ route('products.show', $product) }}" class="block">
                                            <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                                                @if ($product->image)
                                                    <img 
                                                        src="{{ $product->image }}" 
                                                        alt="{{ $product->name }}" 
                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                                        loading="lazy"
                                                        decoding="async"
                                                        width="400"
                                                        height="400"
                                                    />
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
                                                            {{ $product->manufacturer }}
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
                                <div class="flex items-center justify-center">
                                    {{ $filteredProducts->links('pagination.livewire-tailwind') }}
                                </div>
                            @endif
                        @endif
                        @endisset

                        <div
                            class="fixed inset-0 z-50 hidden lg:hidden"
                            x-data="{ open: false }"
                            x-on:toggle-mobile-filters.window="open = !open"
                            :class="{ 'flex': open }"
                        >
                            <div class="flex flex-col h-full bg-white dark:bg-zinc-900 w-80 max-w-full">
                                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 px-4 py-4">
                                    <flux:heading level="2">Browse & Filter</flux:heading>
                                    <flux:button
                                        variant="subtle"
                                        @click="open = false"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </flux:button>
                                </div>

                                <div class="flex-1 overflow-y-auto p-4 space-y-6">
                                    <div class="space-y-4">
                                        <flux:heading level="3">Select Your Bike</flux:heading>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <flux:heading level="4" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Manufacturer</flux:heading>
                                                <div class="relative">
                                                    <select
                                                        wire:model.live="selectedManufacturer"
                                                        wire:loading.attr="disabled"
                                                        class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                                    >
                                                        <option value="">Choose Manufacturer</option>
                                                        @foreach ($availableBikeManufacturers as $m)
                                                            <option value="{{ $m }}">{{ $m }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 pointer-events-none opacity-0" wire:loading wire:target="selectedManufacturer">
                                                        <svg class="shrink-0 w-5 h-5 animate-spin text-zinc-600 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <flux:heading level="4" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Model</flux:heading>
                                                <div class="relative">
                                                    <select
                                                        wire:model.live="selectedModel"
                                                        wire:loading.attr="disabled"
                                                        class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400 disabled:opacity-50"
                                                        @if(!$selectedManufacturer) disabled @endif
                                                    >
                                                        <option value="">Choose Model</option>
                                                        @if($selectedManufacturer)
                                                            @foreach ($availableBikeModels as $model)
                                                                <option value="{{ $model }}">{{ $model }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 pointer-events-none opacity-0" wire:loading wire:target="selectedModel">
                                                        <svg class="shrink-0 w-5 h-5 animate-spin text-zinc-600 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <flux:heading level="4" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Year</flux:heading>
                                                <div class="relative">
                                                    <select
                                                        wire:model.live="selectedYear"
                                                        wire:loading.attr="disabled"
                                                        class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400 disabled:opacity-50"
                                                        @if(!$selectedModel) disabled @endif
                                                    >
                                                        <option value="">Choose Year</option>
                                                        @if($selectedModel)
                                                            @foreach ($availableBikeYears as $year)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 pointer-events-none opacity-0" wire:loading wire:target="selectedYear">
                                                        <svg class="shrink-0 w-5 h-5 animate-spin text-zinc-600 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Progressive Mobile Navigation Buttons -->
                                            <div class="space-y-3 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                                                
                                                <!-- Manufacturer Button -->
                                                @if($selectedManufacturer)
                                                    <div class="relative">
                                                        <flux:button 
                                                            variant="primary"
                                                            wire:click="navigateToManufacturer"
                                                            class="w-full flex items-center justify-center py-3 text-base min-h-[44px]"
                                                            @click="open = false"
                                                        >
                                                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                            <span class="text-center">All {{ $selectedManufacturer }} Products</span>
                                                        </flux:button>
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToManufacturer">
                                                            <svg class="shrink-0 w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Model Button -->
                                                @if($selectedManufacturer && $selectedModel)
                                                    <div class="relative">
                                                        <flux:button 
                                                            variant="primary"
                                                            wire:click="navigateToModel"
                                                            class="w-full flex items-center justify-center py-3 text-base min-h-[44px]"
                                                            @click="open = false"
                                                        >
                                                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                            <span class="text-center">All {{ $selectedManufacturer }} {{ $selectedModel }} Products</span>
                                                        </flux:button>
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToModel">
                                                            <svg class="shrink-0 w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Year Button -->
                                                @if($selectedManufacturer && $selectedModel && $selectedYear)
                                                    <div class="relative">
                                                        <flux:button 
                                                            variant="primary"
                                                            wire:click="navigateToBike"
                                                            class="w-full flex items-center justify-center py-3 text-base min-h-[44px]"
                                                            @click="open = false"
                                                        >
                                                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                            <span class="text-center">{{ $selectedManufacturer }} {{ $selectedModel }} {{ $selectedYear }} Products</span>
                                                        </flux:button>
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToBike">
                                                            <svg class="shrink-0 w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                        <flux:heading level="3">Product Filters</flux:heading>
                                    </div>
                                    <div class="space-y-2">
                                        <flux:heading level="3">Search</flux:heading>
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-[17px] h-[17px] text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input
                                                wire:model.debounce.300ms="search"
                                                type="text"
                                                placeholder="Search products..."
                                                class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-10 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                            />
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <flux:heading level="3">Category</flux:heading>
                                        <select
                                            wire:model.lazy="category"
                                            class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                        >
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <flux:heading level="3">Type</flux:heading>
                                        <select
                                            wire:model.lazy="type"
                                            class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                        >
                                            <option value="">All Types</option>
                                            @foreach ($availableTypes as $t)
                                                <option value="{{ $t }}">{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <flux:heading level="3">Manufacturer</flux:heading>
                                        <select
                                            wire:model.lazy="manufacturer"
                                            class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                        >
                                            <option value="">All Manufacturers</option>
                                            @foreach ($availableManufacturers as $m)
                                                <option value="{{ $m }}">{{ $m }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <flux:heading level="3">Price Range</flux:heading>
                                        <div class="space-y-3">
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">$</span>
                                                <input
                                                    wire:model.live="minPrice"
                                                    type="number"
                                                    placeholder="Min price"
                                                    class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-8 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                                />
                                            </div>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">$</span>
                                                <input
                                                    wire:model.live="maxPrice"
                                                    type="number"
                                                    placeholder="Max price"
                                                    class="w-full rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-8 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-950 dark:focus:ring-zinc-400"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    @if ($search || $category || $type || $manufacturer || $minPrice || $maxPrice)
                                        <flux:button
                                            variant="subtle"
                                            wire:click="clearFilters"
                                            class="w-full"
                                            @click="open = false"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Clear Filters
                                        </flux:button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </flux:main>
        </div>
    </div>

