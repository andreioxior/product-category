@php
    use App\Services\ImageUrlService;
    
    $localImage = asset('images/products/' . basename($src ?? ''));
    $placeholderUrl = asset('images/product-placeholder.svg');
    $useLocalFile = $src && ImageUrlService::localImageExists($src);
@endphp

@if($src)
    <picture>
        @if($useLocalFile)
            <source srcset="{{ $localImage }}" type="image/webp">
            <img 
                src="{{ $localImage }}" 
                alt="{{ $alt ?? 'Product image' }}"
                width="{{ $width ?? '300' }}"
                height="{{ $height ?? '300' }}"
                loading="{{ $loading ?? 'lazy' }}"
                class="w-full h-full object-cover {{ $class }}"
                {{ $attributes }}
                onerror="this.onerror=null; this.src='{{ $placeholderUrl }}'; this.classList.add('bg-zinc-100');"
            />
        @else
            <img 
                src="{{ $src }}" 
                alt="{{ $alt ?? 'Product image' }}"
                width="{{ $width ?? '300' }}"
                height="{{ $height ?? '300' }}"
                loading="{{ $loading ?? 'lazy' }}"
                class="w-full h-full object-cover {{ $class }}"
                {{ $attributes }}
                onerror="this.onerror=null; this.src='{{ $placeholderUrl }}'; this.classList.add('bg-zinc-100');"
            />
        @endif
    </picture>
@else
    <!-- Placeholder for missing images -->
    <div class="w-full h-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 {{ $class }}">
        <svg class="w-20 h-20 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <span class="text-zinc-500 dark:text-zinc-400 ml-2">No Image</span>
    </div>
@endif