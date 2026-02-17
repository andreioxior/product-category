<div class="px-4 sm:px-6 lg:px-8 py-8">
            <flux:heading level="1">Order Management</flux:heading>

        <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow-sm">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <flux:label>Search Orders</flux:label>
                        <flux:input wire:model.debounce.300ms="search" type="text" placeholder="Order #, name, email..."></flux:input>
                    </div>
                    <div>
                        <flux:label>Status Filter</flux:label>
                        <flux:select wire:model.lazy="statusFilter">
                            <option value="">All Statuses</option>
                            @foreach ($this->statusOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div>
                        <flux:label>Show</flux:label>
                        <flux:select wire:model.lazy="showArchived">
                            <option value="0">Active Orders</option>
                            <option value="1">Archived Orders</option>
                        </flux:select>
                    </div>
                    <div class="flex items-end">
                        @if ($search || $statusFilter)
                            <flux:button variant="subtle" wire:click="search = ''; statusFilter = null" class="w-full">
                                Clear Filters
                            </flux:button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th>
                                <button wire:click="sortBy('id')" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider flex items-center gap-1">
                                    Order #
                                    @if ($sortField === 'id')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button wire:click="sortBy('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider flex items-center gap-1">
                                    Customer
                                    @if ($sortField === 'customer_name')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button wire:click="sortBy('total')" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider flex items-center gap-1">
                                    Total
                                    @if ($sortField === 'total')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                            <th>
                                <button wire:click="sortBy('created_at')" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider flex items-center gap-1">
                                    Date
                                    @if ($sortField === 'created_at')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse ($this->orders as $order)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium">#{{ $order->id }}</span>
                                    @if ($order->archived_at)
                                        <span class="ml-2 text-xs text-zinc-500">(Archived)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $order->customer_name }}</span>
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $order->customer_email }}</span>
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $order->customer_phone }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-green-600 dark:text-green-400">${{ number_format($order->total, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" class="rounded-md border-zinc-300 text-sm focus:ring-zinc-500 focus:border-zinc-500">
                                        @foreach ($this->statusOptions as $key => $label)
                                            <option value="{{ $key }}" {{ $order->order_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <flux:button
                                            variant="subtle"
                                            size="sm"
                                            @click="$dispatch('open-order-detail', {{ $order->id }})"
                                        >
                                            View
                                        </flux:button>
                                        <flux:button
                                            variant="subtle"
                                            size="sm"
                                            wire:click="archiveOrder({{ $order->id }})"
                                            title="{{ $order->archived_at ? 'Unarchive' : 'Archive' }}"
                                        >
                                            @if ($order->archived_at)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                </svg>
                                            @endif
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>

                            <tr x-data="{ open: false }" class="bg-zinc-50 dark:bg-zinc-800/30" x-on:open-order-detail.window="open = $event.detail === {{ $order->id }}" x-show="open">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <h4 class="font-semibold mb-2">Shipping Address</h4>
                                                <p class="text-sm">{{ $order->customer_address }}</p>
                                                <p class="text-sm">{{ $order->customer_city }}, {{ $order->customer_state }} {{ $order->customer_zip }}</p>
                                                <p class="text-sm">{{ $order->customer_country }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold mb-2">Payment Details</h4>
                                                <p class="text-sm"><strong>Method:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : $order->payment_method }}</p>
                                                <p class="text-sm"><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                                                @if ($order->notes)
                                                    <p class="text-sm"><strong>Notes:</strong> {{ $order->notes }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="font-semibold mb-2">Order Items</h4>
                                            <div class="space-y-2">
                                                @foreach ($order->items as $item)
                                                    <div class="flex items-center justify-between py-2 border-b border-zinc-200 dark:border-zinc-700 last:border-0">
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium">{{ $item->product_name }}</p>
                                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Qty: {{ $item->quantity }} × ${{ number_format($item->product_price, 2) }}</p>
                                                        </div>
                                                        <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                                            ${{ number_format($item->subtotal, 2) }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                            <span class="text-sm">Subtotal:</span>
                                            <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm">Shipping:</span>
                                            <span class="font-medium">
                                                @if ($order->shipping_cost == 0)
                                                    FREE
                                                @else
                                                    ${{ number_format($order->shipping_cost, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                            <span class="font-semibold">Total:</span>
                                            <span class="font-bold text-green-600 dark:text-green-400">
                                                ${{ number_format($order->total, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-zinc-500 dark:text-zinc-400">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $this->orders->links('pagination.livewire-tailwind') }}
            </div>
        </div>
    </div>
