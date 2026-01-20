<div>
    <div>
        <livewire:cart />

        <flux:main>
            <div class="px-4 sm:px-6 lg:px-8 py-8">
                <flux:heading level="1">Checkout</flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                    Complete your order below
                </flux:text>

                @if (session('error'))
                    <div class="mt-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                        <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <form wire:submit="placeOrder" class="space-y-6">
                            <div>
                                <flux:heading level="2">Contact Information</flux:heading>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <flux:label>Full Name</flux:label>
                                        <flux:input wire:model="customerName" type="text" required />
                                        @error('customerName')
                                            <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                        @enderror
                                    </div>
                                    <div>
                                        <flux:label>Email</flux:label>
                                        <flux:input wire:model="customerEmail" type="email" required />
                                        @error('customerEmail')
                                            <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                        @enderror
                                    </div>
                                    <div>
                                        <flux:label>Phone</flux:label>
                                        <flux:input wire:model="customerPhone" type="tel" required />
                                        @error('customerPhone')
                                            <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <flux:heading level="2">Shipping Address</flux:heading>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <flux:label>Street Address</flux:label>
                                        <flux:input wire:model="customerAddress" type="text" required />
                                        @error('customerAddress')
                                            <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <flux:label>City</flux:label>
                                            <flux:input wire:model="customerCity" type="text" required />
                                            @error('customerCity')
                                                <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                            @enderror
                                        </div>
                                        <div>
                                            <flux:label>State</flux:label>
                                            <flux:input wire:model="customerState" type="text" required />
                                            @error('customerState')
                                                <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                            @enderror
                                        </div>
                                        <div>
                                            <flux:label>ZIP Code</flux:label>
                                            <flux:input wire:model="customerZip" type="text" required />
                                            @error('customerZip')
                                                <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <flux:label>Country</flux:label>
                                        <flux:select wire:model="customerCountry" required>
                                            <option value="USA">United States</option>
                                            <option value="CAN">Canada</option>
                                            <option value="MEX">Mexico</option>
                                            <option value="GBR">United Kingdom</option>
                                            <option value="DEU">Germany</option>
                                            <option value="FRA">France</option>
                                            <option value="AUS">Australia</option>
                                            <option value="OTHER">Other</option>
                                        </flux:select>
                                        @error('customerCountry')
                                            <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <flux:heading level="2">Payment Method</flux:heading>
                                <div class="mt-4 space-y-3">
                                    <div class="relative">
                                        <input type="radio" name="payment_method" value="cod" wire:model="paymentMethod" id="cod" checked class="peer sr-only" />
                                        <label for="cod" class="block cursor-pointer rounded-lg border-2 border-zinc-200 dark:border-zinc-700 p-4 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium">Cash on Delivery</p>
                                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pay when your order arrives</p>
                                                    </div>
                                                </div>
                                                <svg class="h-5 w-5 text-green-500 opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <flux:heading level="2">Order Notes (Optional)</flux:heading>
                                <div class="mt-4">
                                    <flux:textarea wire:model="notes" rows="3" placeholder="Any special instructions for your order..." />
                                    @error('notes')
                                        <flux:text class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</flux:text>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="sticky top-4">
                            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-6">
                                <flux:heading level="2">Order Summary</flux:heading>
                                <flux:text class="text-zinc-500 dark:text-zinc-400 mt-1">
                                    {{ $this->cartItemsCount }} item(s)
                                </flux:text>

                                <div class="mt-4 space-y-3">
                                    @foreach ($cart as $item)
                                        <div class="flex items-start gap-3">
                                            @if ($item['image'])
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-md object-cover" />
                                            @else
                                                <div class="w-16 h-16 rounded-md bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-sm truncate">{{ $item['name'] }}</p>
                                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Qty: {{ $item['quantity'] }}</p>
                                                <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 space-y-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                                        <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                                        <span class="font-medium">
                                            @if ($shipping == 0)
                                                FREE
                                            @else
                                                ${{ number_format($shipping, 2) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                        <flux:heading level="3">Total</flux:heading>
                                        <flux:text size="2xl" class="font-bold text-green-600 dark:text-green-400">
                                            ${{ number_format($total, 2) }}
                                        </flux:text>
                                    </div>
                                </div>

                                @if ($shipping > 0)
                                    <div class="mt-4 rounded-md bg-zinc-100 dark:bg-zinc-700 p-3">
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                            Free shipping on orders over $100
                                        </p>
                                    </div>
                                @endif

                                <flux:button
                                    variant="primary"
                                    type="submit"
                                    wire:click="placeOrder"
                                    class="w-full mt-6"
                                >
                                    Place Order
                                </flux:button>

                                <flux:button
                                    variant="subtle"
                                    href="{{ route('products') }}"
                                    class="w-full mt-3"
                                >
                                    Continue Shopping
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </flux:main>
    </div>
</div>
