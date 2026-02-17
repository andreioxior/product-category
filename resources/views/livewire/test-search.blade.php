<div class="p-4 bg-white dark:bg-zinc-900 rounded-lg shadow">
    <h3 class="text-lg font-bold mb-2">Test Search Component</h3>
    
    <flux:input
        wire:model.debounce.300ms="search"
        placeholder="Type to test..."
    />
    
    <div class="mt-2 text-sm">
        Search: "{{ $search }}" | Results: {{ count($results) }}
    </div>
    
    @if(count($results) > 0)
        <ul class="mt-2 space-y-1">
            @foreach($results as $result)
                <li class="p-2 bg-zinc-100 dark:bg-zinc-800 rounded">
                    {{ $result['name'] }}
                </li>
            @endforeach
        </ul>
    @endif
</div>