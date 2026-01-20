<form wire:submit.prevent="save" class="max-w-2xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <flux:heading level="2">
                    {{ $isEditing ? 'Edit Category' : 'Add Category' }}
                </flux:heading>
                <flux:button
                    variant="subtle"
                    href="{{ route('admin.categories') }}"
                >
                    Cancel
                </flux:button>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                <div class="space-y-2">
                    <flux:label for="category_name">Name</flux:label>
                    <flux:input
                        id="category_name"
                        wire:model="name"
                        type="text"
                        placeholder="Category name"
                    ></flux:input>
                    @error('name')
                        <flux:text color="danger" class="text-sm">{{ $message }}</flux:text>
                    @enderror
                </div>

                <div class="space-y-2">
                    <flux:label for="category_description">Description</flux:label>
                    <flux:textarea
                        id="category_description"
                        wire:model="description"
                        placeholder="Category description (optional)"
                        rows="3"
                    ></flux:textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <flux:button
                        variant="subtle"
                        href="{{ route('admin.categories') }}"
                    >
                        Cancel
                    </flux:button>
                    <flux:button type="submit">
                        {{ $isEditing ? 'Update' : 'Create' }} Category
                    </flux:button>
                </div>
            </div>
        </form>
