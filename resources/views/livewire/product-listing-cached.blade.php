<div>
    @push('meta')
        <meta name="description" content="Browse our professional bike parts and accessories. Find mountain bikes, road bikes, components, and more at competitive prices." />
        <meta property="og:title" content="{{ $pageTitle }}" />
        <meta property="og:description" content="Browse our professional bike parts and accessories. Find mountain bikes, road bikes, components, and more." />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary_large_image" />
    @endpush
    
    <livewire:cart />

    <flux:spacer size="4" />

    <flux:main>
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:gap-8">
                <!-- Sidebar with Filters -->
                <aside class="hidden lg:block w-64 shrink-0">
                    <div class="sticky top-4 space-y-6">
                        <!-- Browse by Bike Section -->
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
                            </div>

                            <!-- Navigation Buttons -->
                            @if($selectedManufacturer)
                                <div class="space-y-2">
                                    <!-- Manufacturer Button -->
                                    <div class="relative">
                                        <button
                                            type="button"
                                            wire:click="navigateToManufacturer"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-950 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-200 focus:outline-none focus:ring-2 focus:ring-zinc-500 dark:focus:ring-zinc-400 transition-colors"
                                        >
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                            <span>View All {{ $selectedManufacturer }} Products</span>
                                        </button>
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0" wire:loading wire:target="navigateToManufacturer">
                                            <svg class="w-4 h-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="space-y-4">
                        <flux:heading level="2">Filters</flux:heading>
                        
                        <div class="space-y-4">
                            <!-- Search -->
                            <div class="space-y-2">
                                <flux:heading level="3">Search</flux:heading>
                                <flux:input 
                                    wire:model.live.debounce.300ms="search" 
                                    placeholder="Search products..." 
                                    clearable
                                >
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </flux:input>
                            </div>

                            <!-- Category Filter -->
                            <div class="space-y-2">
                                <flux:heading level="3">Category</flux:heading>
                                <flux:select wire:model.live="selectedCategoryId">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </flux:select>
                            </div>

                            <!-- Sort -->
                            <div class="space-y-2">
                                <flux:heading level="3">Sort By</flux:heading>
                                <flux:select wire:model.live="sortField">
                                    <option value="name_asc">Name (A-Z)</option>
                                    <option value="name_desc">Name (Z-A)</option>
                                    <option value="price_asc">Price (Low to High)</option>
                                    <option value="price_desc">Price (High to Low)</option>
                                    <option value="newest">Newest First</option>
                                </flux:select>
                            </div>

                            <!-- Clear Filters -->
                            @if($search || $selectedCategoryId || $sortField !== 'name_asc')
                                <flux:button 
                                    variant="outline" 
                                    size="sm" 
                                    wire:click="resetFilters"
                                    class="w-full"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear All Filters
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1">
                    <!-- Breadcrumb -->
                    <nav class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                        <ol class="flex items-center space-x-2 text-sm text-gray-600">
                            <li>
                                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <span class="text-gray-900 font-medium">Products</span>
                            </li>
                        </ol>
                    </nav>

                    <!-- Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Our Products</h1>
                        <p class="text-gray-600">Browse our extensive collection of bikes, components, and accessories</p>
                    </div>

                    <!-- Loading State -->
                    @if($this->loading)
                        <div class="flex justify-center items-center h-64">
                            <div class="text-center">
                                <svg class="w-8 h-8 animate-spin text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <p class="text-gray-600">Loading products...</p>
                            </div>
                        </div>
                    @else
                        <!-- Stats Bar -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-6">
                                    <div>
                                        <span class="text-sm text-gray-600">Showing</span>
                                        <span class="font-semibold text-gray-900 mx-1">{{ $totalProductsCount }}</span>
                                        <span class="text-sm text-gray-600">products</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Categories:</span>
                                        <span class="font-semibold text-gray-900 mx-1">{{ $categories->count() }}</span>
                                    </div>
                                    @if($selectedCategoryId)
                                        <div class="bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                            {{ $selectedCategory->name }}
                                        </div>
                                    @endif
                                </div>
                                
                                @if($search)
                                    <div class="bg-yellow-50 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Searching for "{{ $search }}"
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Products Grid -->
                        @if($products->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($products as $product)
                                    <x-cached-product-card :product="$product" />
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($products->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $products->links('pagination.livewire-tailwind') }}
                                </div>
                            @endif
                        @else
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                                <p class="text-gray-600 mb-4">Try adjusting your search criteria or filters</p>
                                <flux:button wire:click="resetFilters" variant="outline">
                                    Clear Filters
                                </flux:button>
                            </div>
                        @endif
                    @endif
                </main>
            </div>
        </div>
    </flux:main>
</div>