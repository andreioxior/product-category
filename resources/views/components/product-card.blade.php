@cache(['product_card_'.$product->id, 3600], ['products', 'categories'])
<div class="group bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
            @if ($product->image)
                <x-optimized-image :product="$product" size="400" class="group-hover:scale-105 transition-transform duration-300" />
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
</div>
@endcache