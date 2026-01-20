<div class="space-y-6">
        <flux:heading level="2">Admin Dashboard</flux:heading>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading level="3">Total Products</flux:heading>
                <div class="mt-2">
                    <flux:text size="4xl" class="font-bold">{{ App\Models\Product::count() }}</flux:text>
                </div>
                <flux:button
                    variant="subtle"
                    size="sm"
                    href="{{ route('admin.products') }}"
                    class="mt-4"
                >
                    Manage Products
                </flux:button>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading level="3">Total Categories</flux:heading>
                <div class="mt-2">
                    <flux:text size="4xl" class="font-bold">{{ App\Models\Category::count() }}</flux:text>
                </div>
                <flux:button
                    variant="subtle"
                    size="sm"
                    href="{{ route('admin.categories') }}"
                    class="mt-4"
                >
                    Manage Categories
                </flux:button>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading level="3">Active Products</flux:heading>
                <div class="mt-2">
                    <flux:text size="4xl" class="font-bold">{{ App\Models\Product::where('is_active', true)->count() }}</flux:text>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading level="3">Total Stock Value</flux:heading>
                <div class="mt-2">
                    <flux:text size="4xl" class="font-bold">${{ number_format(App\Models\Product::sum('price'), 2) }}</flux:text>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading level="3">Recent Products</flux:heading>
                <div class="mt-4 space-y-3">
                    @php
                        $recentProducts = App\Models\Product::latest()->take(5)->get();
                    @endphp
                    @if ($recentProducts->count() > 0)
                        @foreach ($recentProducts as $product)
                            <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">${{ number_format($product->price, 2) }}</p>
                                </div>
                                <flux:button
                                    variant="subtle"
                                    size="sm"
                                    href="{{ route('admin.products.edit', $product) }}"
                                >
                                    Edit
                                </flux:button>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">No products yet.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                <flux:heading level="3">Quick Actions</flux:heading>
                <div class="mt-4 grid grid-cols-1 gap-3">
                    <flux:button href="{{ route('admin.products.create') }}">
                        Add New Product
                    </flux:button>
                    <flux:button href="{{ route('admin.categories.create') }}">
                        Add New Category
                    </flux:button>
                    <flux:button href="{{ route('admin.products') }}">
                        View All Products
                    </flux:button>
                    <flux:button href="{{ route('admin.categories') }}">
                        View All Categories
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
