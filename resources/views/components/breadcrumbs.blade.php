@props(['items' => []])

<div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
    <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Home</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7m0 0l7 7m-7 7h18" />
    </svg>
    <a href="{{ route('products') }}" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Products</a>
    @foreach($items as $item)
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7m0 0l7 7m-7 7h18" />
        </svg>
        @if (isset($item['url']) && $item['url'])
            <a href="{{ $item['url'] }}" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">{{ $item['label'] }}</a>
        @else
            <span class="text-zinc-900 dark:text-zinc-100 font-medium">{{ $item['label'] }}</span>
        @endif
    @endforeach
</div>
