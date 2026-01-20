<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.simple')]
class Checkout extends Component
{
    #[Validate('required|string|max:255')]
    public string $customerName = '';

    #[Validate('required|email|max:255')]
    public string $customerEmail = '';

    #[Validate('required|string|max:20')]
    public string $customerPhone = '';

    #[Validate('required|string|max:255')]
    public string $customerAddress = '';

    #[Validate('required|string|max:100')]
    public string $customerCity = '';

    #[Validate('required|string|max:100')]
    public string $customerState = '';

    #[Validate('required|string|max:20')]
    public string $customerZip = '';

    #[Validate('required|string|max:100')]
    public string $customerCountry = 'USA';

    #[Validate('nullable|string|max:1000')]
    public ?string $notes = '';

    #[Validate('required|in:cod')]
    public string $paymentMethod = 'cod';

    public array $cart = [];

    public float $subtotal = 0;

    public float $shipping = 0;

    public float $total = 0;

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);

        if (empty($this->cart)) {
            $this->redirect(route('products'), navigate: true);
        }

        $this->calculateTotals();

        $user = Auth::user();
        if ($user) {
            $this->customerName = $user->name ?? '';
            $this->customerEmail = $user->email ?? '';
        }
    }

    public function calculateTotals(): void
    {
        $this->subtotal = array_reduce($this->cart, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        $this->shipping = $this->subtotal > 100 ? 0 : 10;
        $this->total = $this->subtotal + $this->shipping;
    }

    public function placeOrder(): void
    {
        $this->validate();

        if (empty($this->cart)) {
            return;
        }

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'customer_address' => $this->customerAddress,
                'customer_city' => $this->customerCity,
                'customer_state' => $this->customerState,
                'customer_zip' => $this->customerZip,
                'customer_country' => $this->customerCountry,
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'paid',
                'order_status' => 'pending',
                'subtotal' => $this->subtotal,
                'shipping_cost' => $this->shipping,
                'total' => $this->total,
                'notes' => $this->notes,
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            session()->forget('cart');

            $this->redirect(route('checkout.success', ['order' => $order->id]), navigate: true);
        } catch (\Exception $e) {
            Log::error('Order placement failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Failed to place order. Please try again.');
        }
    }

    public function getCartItemsCountProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.checkout');
    }
}
