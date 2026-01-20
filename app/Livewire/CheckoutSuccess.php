<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.simple')]
class CheckoutSuccess extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load('items');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.checkout-success');
    }
}
