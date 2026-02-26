@php
    $imageUrl = \App\Services\ImageUrlService::getProductImageUrl($product);
    $altText = $product->name . ' - ' . $product->category->name ?? 'Product';
@endphp

<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <x-optimized-image 
            src="{{ $imageUrl }}" 
            alt="{{ $altText }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            width="300"
            height="300"
            loading="lazy"
        />
        
        @if($product->stock_quantity <= 10)
            <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                {{ $product->stock_quantity <= 5 ? 'Only ' . $product->stock_quantity . ' left!' : 'Low Stock' }}
            </div>
        @endif
    </div>
    
    <div class="p-4">
        <h3 class="font-semibold text-lg text-gray-900 mb-1 line-clamp-2">
            {{ $product->name }}
        </h3>
        
        <p class="text-sm text-gray-600 mb-2">
            {{ $product->category->name ?? 'Uncategorized' }}
        </p>
        
        <div class="flex items-center justify-between mb-3">
            <div class="text-xl font-bold text-gray-900">
                ${{ number_format($product->price, 2) }}
            </div>
            
            @if($product->manufacturer)
                <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                    {{ $product->manufacturer }}
                </div>
            @endif
        </div>
        
        <div class="flex gap-2">
            <button
                wire:click="addToCart({{ $product->id }})"
                type="button"
                class="flex-1 inline-flex items-center justify-center gap-1 rounded-md bg-zinc-950 dark:bg-zinc-100 px-3 py-1.5 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-200 focus:outline-none focus:ring-2 focus:ring-zinc-500 dark:focus:ring-zinc-400 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Add to Cart
            </button>
            
            <a
                href="{{ route('products.show', $product->id) }}"
                class="inline-flex items-center justify-center rounded-md border border-zinc-200 dark:border-zinc-700 px-3 py-1.5 text-sm font-medium text-zinc-900 dark:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-500 dark:focus:ring-zinc-400 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
        </div>
    </div>
</div>