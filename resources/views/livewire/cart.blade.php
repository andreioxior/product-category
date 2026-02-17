<div>
    <div class="fixed top-4 right-4 z-40">
        <flux:button
            variant="subtle"
            wire:click="toggleCart"
            class="relative"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 0 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if ($this->cartItemsCount > 0)
                <span class="absolute -top-2 -right-2 flex items-center justify-center w-6 h-6 rounded-full bg-red-500 text-white text-xs font-bold">
                    {{ $this->cartItemsCount }}
                </span>
            @endif
        </flux:button>
    </div>

          <div
          class="fixed inset-0 z-50"
          x-data="{ open: @js($showCart) }"
          x-show="open"
          x-on:toggle-cart.window="open = !open"
          x-on:cart-updated.window="open = true"
          :class="{ 'flex': open }"
      >
        <div class="fixed inset-0 bg-black/50" x-show="open" @click="open = false"></div>

        <div class="fixed inset-y-0 right-0 w-full max-w-md flex flex-col bg-white dark:bg-zinc-900 shadow-xl transform transition-transform duration-300"
            x-show="open"
            x-transition:enter="translate-x-full"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="translate-x-0"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 p-4">
                <flux:heading level="2">Shopping Cart</flux:heading>
                <flux:button variant="subtle" @click="open = false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </flux:button>
            </div>

            @if (count($cart) > 0)
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    @foreach ($cart as $item)
                        <div class="flex items-center gap-4 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            @if ($item['image'])
                                <img 
                                    src="{{ $item['image'] }}" 
                                    alt="{{ $item['name'] }}" 
                                    class="w-20 h-20 rounded-md object-cover" 
                                    loading="lazy"
                                    decoding="async"
                                    width="80"
                                    height="80"
                                />
                            @else
                                <div class="w-20 h-20 rounded-md bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm line-clamp-1">{{ $item['name'] }}</h4>
                                 <flux:text class="text-green-600 dark:text-green-400 font-medium mt-1">
                                    ${{ number_format($item['price'], 2) }}
                                </flux:text>
                                 <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                                    Total: ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                </flux:text>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-2">
                                     <flux:button
                                        variant="subtle"
                                        wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </flux:button>
                                    <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                                     <flux:button
                                        variant="subtle"
                                        wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </flux:button>
                                </div>
                                <flux:button
                                    variant="danger"
                                    wire:click="removeFromCart({{ $item['id'] }})"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </flux:button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 0 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <flux:heading level="3">Your cart is empty</flux:heading>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                        Add some products to get started.
                    </flux:text>
                    <div class="mt-6">
                        <flux:button variant="subtle" href="{{ route('products') }}">
                            Browse Products
                        </flux:button>
                    </div>
                </div>
            @endif

            @if (count($cart) > 0)
                <div class="border-t border-zinc-200 dark:border-zinc-700 p-4 space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-600 dark:text-zinc-400">Subtotal ({{ $this->cartItemsCount }} items)</span>
                            <span class="font-medium">${{ number_format($this->cartTotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                            <span class="font-medium">Calculated at checkout</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:heading level="3">Total</flux:heading>
                        <flux:text size="2xl" class="font-bold text-green-600 dark:text-green-400">
                            ${{ number_format($this->cartTotal, 2) }}
                        </flux:text>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <flux:button variant="subtle" wire:click="clearCart" class="flex-1">
                            Clear Cart
                        </flux:button>
                        <flux:button variant="primary" href="{{ route('checkout') }}" class="flex-1">
                            Checkout
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
