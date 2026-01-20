<div>
    <div>
        <livewire:cart />

        <flux:main>
            <div class="px-4 sm:px-6 lg:px-8 py-8">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <flux:heading level="1" class="mt-6">Order Confirmed!</flux:heading>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                            Thank you for your purchase. Your order has been placed successfully.
                        </flux:text>

                        <div class="mt-4 rounded-md bg-zinc-100 dark:bg-zinc-800 p-4">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                Order Number: <span class="font-semibold">#{{ $order->id }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 bg-zinc-50 dark:bg-zinc-800 rounded-lg p-6">
                        <flux:heading level="2">Order Details</flux:heading>

                        <div class="mt-4 space-y-4">
                            <div>
                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Shipping Address</flux:text>
                                <div class="mt-1">
                                    <p class="font-medium">{{ $order->customer_name }}</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $order->customer_address }}</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $order->customer_city }}, {{ $order->customer_state }} {{ $order->customer_zip }}
                                    </p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $order->customer_country }}</p>
                                </div>
                            </div>

                            <div>
                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Contact Information</flux:text>
                                <div class="mt-1 space-y-1">
                                    <p class="text-sm">{{ $order->customer_email }}</p>
                                    <p class="text-sm">{{ $order->customer_phone }}</p>
                                </div>
                            </div>

                            <div>
                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Payment Method</flux:text>
                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-md bg-green-100 dark:bg-green-900 px-2 py-1 text-xs font-medium text-green-900 dark:text-green-100">
                                        Cash on Delivery
                                    </span>
                                </div>
                            </div>

                            @if ($order->notes)
                                <div>
                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Order Notes</flux:text>
                                    <div class="mt-1">
                                        <p class="text-sm">{{ $order->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 bg-zinc-50 dark:bg-zinc-800 rounded-lg p-6">
                        <flux:heading level="2">Items Ordered</flux:heading>

                        <div class="mt-4 space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <p class="font-medium">{{ $item->product_name }}</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-medium text-green-600 dark:text-green-400">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 space-y-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                                <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                                <span class="font-medium">
                                    @if ($order->shipping_cost == 0)
                                        FREE
                                    @else
                                        ${{ number_format($order->shipping_cost, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                <flux:heading level="3">Total</flux:heading>
                                <flux:text size="2xl" class="font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($order->total, 2) }}
                                </flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <flux:button variant="primary" href="{{ route('products') }}" class="flex-1">
                            Continue Shopping
                        </flux:button>
                    </div>
                </div>
            </div>
        </flux:main>
    </div>
</div>
