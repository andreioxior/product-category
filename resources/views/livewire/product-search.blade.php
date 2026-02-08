<div class="relative w-full max-w-md">
    <!-- Debug: Component rendered, Search="{{ $search }}", ShowSuggestions="{{ $showSuggestions ? 'true' : 'false' }}", SuggestionsCount="{{ count($suggestions) }}" -->
    <flux:input
        wire:model.live="search"
        placeholder="Search products..."
        x-on:keydown.enter="$wire.performSearch()"
        x-ref="searchInput"
    >
        <x-slot:leading>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </x-slot:leading>
        <x-slot:trailing x-show="{{ $search !== '' }}">
            <button
                x-on:click="$wire.set('search', '')"
                class="text-zinc-400 hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </x-slot:trailing>
    </flux:input>

    @if($showSuggestions && count($suggestions) > 0)
        <div class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <ul class="py-2 max-h-96 overflow-y-auto">
                @foreach($suggestions as $suggestion)
                    <li class="px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 cursor-pointer transition-colors">
                        <button
                            wire:click="selectSuggestion('{{ $suggestion['name'] }}')"
                            class="w-full text-left"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">
                                        {{ $suggestion['name'] }}
                                    </p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate mt-0.5">
                                        {{ $suggestion['display_text'] }}
                                    </p>
                                </div>
                                @if($suggestion['category'])
                                    <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200">
                                        {{ $suggestion['category'] }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="px-4 py-2 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700">
                <button
                    wire:click="performSearch()"
                    class="w-full text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors flex items-center justify-center gap-2"
                >
                    <span>View all results for "{{ $search }}"</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    @elseif($showSuggestions && strlen($search) >= 2 && count($suggestions) === 0)
        <div class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 p-4 text-center">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                No products found for "{{ $search }}"
            </p>
        </div>
    @endif
</div>


