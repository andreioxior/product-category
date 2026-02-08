<div class="p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Search Test</h2>
    
    <flux:input
        wire:model.live="search"
        placeholder="Type to search..."
    />
    
    @if($search)
        <div class="mt-4">
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                Searching for: "{{ $search }}"
            </p>
            
            @if(count($results) > 0)
                <div class="space-y-2">
                    @foreach($results as $result)
                        @if(isset($result['error']))
                            <div class="text-red-500 text-sm">
                                Error: {{ $result['error'] }}
                            </div>
                        @else
                            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded">
                                <p class="font-medium">{{ $result['name'] }}</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $result['manufacturer'] }} - {{ $result['model'] }} ({{ $result['year'] }})
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    No results found
                </p>
            @endif
        </div>
    @endif
</div>