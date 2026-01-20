<div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading level="2">Categories</flux:heading>
            <flux:button href="{{ route('admin.categories.create') }}">
                Add Category
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <flux:input
                    wire:model.live="search"
                    placeholder="Search categories..."
                    type="text"
                />
            </div>

            @if ($categories->count() > 0)
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($categories as $category)
                        <div class="flex items-center justify-between p-4 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            <div class="flex-1">
                                <h3 class="font-semibold text-base">{{ $category->name }}</h3>
                                @if ($category->description)
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-1">
                                        {{ $category->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    variant="subtle"
                                    size="sm"
                                    href="{{ route('admin.categories.edit', $category) }}"
                                >
                                    Edit
                                </flux:button>
                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    wire:click="delete({{ $category->id }})"
                                    wire:confirm="Are you sure you want to delete this category?"
                                >
                                    Delete
                                </flux:button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="p-12 text-center text-zinc-500 dark:text-zinc-400">
                    No categories found.
                </div>
            @endif
        </div>
    </div>
