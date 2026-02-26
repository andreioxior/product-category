<div>
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-4">
                    Premium Bike Parts & Accessories
                </h1>
                <p class="text-lg md:text-xl text-zinc-300 max-w-2xl mx-auto mb-8">
                    Discover our collection of {{ $totalProductsCount }}+ quality products for every rider
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium rounded-full bg-white text-zinc-900 hover:bg-zinc-100 transition-colors">
                        Shop Now
                    </a>
                    <a href="#categories" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium rounded-full border border-zinc-600 text-white hover:bg-zinc-800 transition-colors">
                        Browse Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    <section id="categories" class="py-16 bg-zinc-50 dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-4">Shop by Category</h2>
                <p class="text-zinc-600 dark:text-zinc-400">Find exactly what you need</p>
            </div>
            
            @if($categories->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('products', ['category' => $category->id]) }}" 
                           class="group bg-white dark:bg-zinc-800 rounded-xl p-6 text-center shadow-sm hover:shadow-lg transition-all duration-300 border border-zinc-200 dark:border-zinc-700">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center group-hover:bg-zinc-900 dark:group-hover:bg-white transition-colors">
                                <svg class="w-6 h-6 text-zinc-600 dark:text-zinc-300 group-hover:text-white dark:group-hover:text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-zinc-900 dark:text-white group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">
                                {{ $category->name }}
                            </h3>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center text-zinc-500">No categories available</p>
            @endif
        </div>
    </section>

    {{-- Featured Products Section --}}
    <section class="py-16 bg-white dark:bg-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Featured Products</h2>
                    <p class="text-zinc-600 dark:text-zinc-400">Handpicked for you</p>
                </div>
                <a href="{{ route('products') }}" class="hidden sm:inline-flex items-center text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                    View All
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @if($featuredProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <x-cached-product-card :product="$product" />
                    @endforeach
                </div>
            @else
                <p class="text-center text-zinc-500">No featured products available</p>
            @endif
        </div>
    </section>

    {{-- New Arrivals Section --}}
    @if($newArrivals->count() > 0)
        <section class="py-16 bg-zinc-50 dark:bg-zinc-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">New Arrivals</h2>
                        <p class="text-zinc-600 dark:text-zinc-400">Fresh from our inventory</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($newArrivals as $product)
                        <x-cached-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Promo/Random Products Section --}}
    @if($promoProducts->count() > 0)
        <section class="py-16 bg-white dark:bg-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="inline-block px-4 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-sm font-medium rounded-full mb-4">
                        Special Offers
                    </span>
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">You Might Also Like</h2>
                    <p class="text-zinc-600 dark:text-zinc-400">Curated picks just for you</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($promoProducts as $product)
                        <x-cached-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA Section --}}
    <section class="py-16 bg-zinc-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Ride?</h2>
            <p class="text-zinc-300 mb-8 max-w-2xl mx-auto">
                Browse our complete collection of bike parts, accessories, and equipment. 
                Quality products for every type of rider.
            </p>
            <a href="{{ route('products') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium rounded-full bg-white text-zinc-900 hover:bg-zinc-100 transition-colors">
                Explore All Products
            </a>
        </div>
    </section>
</div>
