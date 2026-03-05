const CART_STORAGE_KEY = 'cart_sync';

window.addEventListener('storage', (event) => {
    if (event.key === CART_STORAGE_KEY && window.Livewire) {
        window.Livewire.dispatch('refresh-cart');
        
        const cart = JSON.parse(event.newValue || '{}');
        const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
        const total = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        updateCartUI(count, total);
    }
});

function updateCartUI(count, total) {
    const cartCountElements = document.querySelectorAll('[data-cart-count]');
    const cartTotalElements = document.querySelectorAll('[data-cart-total]');
    
    cartCountElements.forEach(el => {
        el.textContent = count;
    });
    
    cartTotalElements.forEach(el => {
        el.textContent = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(total);
    });
}

window.syncCartAcrossTabs = (cart) => {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    localStorage.removeItem(CART_STORAGE_KEY);
};

document.addEventListener('livewire:init', () => {
    Livewire.on('cart-updated', (cart) => {
        if (cart) {
            window.syncCartAcrossTabs(cart);
        }
    });
});
