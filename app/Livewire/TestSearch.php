<?php

namespace App\Livewire;

use Livewire\Component;

class TestSearch extends Component
{
    public string $search = '';
    public array $results = [];

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            $this->results = [
                ['name' => 'Test Product 1', 'id' => 1],
                ['name' => 'Test Product 2', 'id' => 2],
                ['name' => 'Test Product 3', 'id' => 3],
            ];
        } else {
            $this->results = [];
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.test-search');
    }
}