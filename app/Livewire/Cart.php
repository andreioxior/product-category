<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Cart extends Component
{
    #[Locked]
    public bool $showCart = false;

    public array $cart = [];

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    #[On('addToCart')]
    public function addToCart(
        $productId = null,
        ?string $name = null,
        $price = null,
        ?string $image = null,
        ?int $variantId = null,
        ?string $variantName = null,
        ?string $sku = null
    ): void {
        if (is_array($productId)) {
            $data = $productId;
            $productId = $data['productId'] ?? null;
            $name = $data['name'] ?? null;
            $price = $data['price'] ?? null;
            $image = $data['image'] ?? null;
            $variantId = $data['variantId'] ?? null;
            $variantName = $data['variantName'] ?? null;
            $sku = $data['sku'] ?? null;
        }

        if (! $productId || ! $name) {
            return;
        }

        $cartKey = $variantId ? "{$productId}-{$variantId}" : (string) $productId;

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'id' => $productId,
                'variantId' => $variantId,
                'name' => $name,
                'price' => (float) $price,
                'image' => $image,
                'quantity' => 1,
                'variantName' => $variantName,
                'sku' => $sku,
            ];
        }

        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->dispatch('cart-updated');
    }

    public function removeFromCart($cartKey): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$cartKey]);
        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->dispatch('cart-updated');
    }

    public function updateQuantity($cartKey, $quantity): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $newQuantity = max(1, (int) $quantity);
            $cart[$cartKey]['quantity'] = $newQuantity;
            session()->put('cart', $cart);
            $this->cart = $cart;
        }

        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->cart = [];
        $this->dispatch('cart-updated');
    }

    public function getCartItemsCountProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function getCartTotalProperty(): float
    {
        return array_reduce($this->cart, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function toggleCart(): void
    {
        $this->showCart = ! $this->showCart;
        $this->dispatch('toggle-cart');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.cart');
    }
}
