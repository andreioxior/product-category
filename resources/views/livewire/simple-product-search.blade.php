<div class="relative w-full max-w-md" x-data="{ open: false }">
    <!-- Search Input with Loading State -->
    <div class="relative">
        <flux:input
            wire:model.live="search"
            placeholder="Search products, manufacturers, models..."
            wire:loading.class="pe-10"
            wire:target="search"
            @focus="open = true"
            @blur="setTimeout(() => open = false, 200)"
        />
        
        <!-- Loading Spinner -->
        <div class="absolute top-0 bottom-0 flex items-center pe-3 end-0 text-zinc-400" wire:loading wire:target="search">
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Search Type Selector -->
    <div class="mt-2 flex flex-wrap gap-1" x-show="search.length >= 2 || open">
        @foreach($searchTypeOptions as $type => $option)
            <flux:button 
                size="sm" 
                wire:click="setSearchType('{{ $type }}')"
                @if($searchType === $type) variant="primary" @else variant="subtle" @endif
                class="text-xs"
            >
                {{ $option['label'] }}
            </flux:button>
        @endforeach
    </div>

    <!-- Suggestions Dropdown -->
    @if(count($suggestions) > 0)
        <div 
            x-show="open && search.length >= 2"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700"
        >
            <!-- Facet Information -->
            @if($facets)
                @if(count($facets) > 0)
                    <div class="px-4 py-2 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                        <div class="flex flex-wrap gap-2 text-xs">
                            @foreach($facets as $field => $values)
                                @if($values)
                                    @if(count($values) > 0)
                                        <span class="text-zinc-600 dark:text-zinc-400">
                                            {{ ucfirst($field) }}: 
                                            @foreach($values as $value)
                                                <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $value['value'] }}</span>@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <!-- Suggestions List -->
            <ul class="max-h-96 overflow-y-auto">
                @foreach($suggestions as $suggestion)
                    <li class="px-4 py-3 hover:bg-zinc-100 dark:hover:bg-zinc-700 cursor-pointer transition-colors border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                        <button
                            wire:click="selectProduct('{{ $suggestion['name'] }}')"
                            class="w-full text-left"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <!-- Product Name with Highlighting -->
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                                        {!! $suggestion['highlighted_name'] !!}
                                    </p>
                                    
                                    <!-- Additional Details -->
                                    @if($suggestion['manufacturer'] || $suggestion['model'] || $suggestion['year'])
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                                            @if($suggestion['manufacturer'])
                                                {!! $suggestion['highlighted_manufacturer'] ?? $suggestion['manufacturer'] !!}
                                            @endif
                                            @if($suggestion['model'])
                                                - {!! $suggestion['highlighted_model'] ?? $suggestion['model'] !!}
                                            @endif
                                            @if($suggestion['year'])
                                                - {!! $suggestion['highlighted_year'] ?? $suggestion['year'] !!}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Field Indicators -->
                                @if(isset($suggestion['field_indicators']))
                                    @if(count($suggestion['field_indicators']) > 0)
                                        <div class="flex flex-wrap gap-1 flex-shrink-0">
                                            @foreach($suggestion['field_indicators'] as $field => $indicator)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $indicator['bg_color'] }}">
                                                    @if(isset($indicator['icon']))
                                                        {!! app(\App\Services\ProductSearchService::class)->getFieldIconSvg($indicator['icon']) !!}
                                                    @endif
                                                    {{ $indicator['label'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Show Results Count -->
            @if(count($suggestions) >= 8)
                <div class="px-4 py-2 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 text-center">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        Showing top {{ count($suggestions) }} results
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>