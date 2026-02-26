<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Homepage::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/orders', \App\Livewire\Admin\OrderManagement::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.orders');

Route::get('/admin/products', \App\Livewire\Admin\ProductList::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.products');

Route::get('/admin/products/create', \App\Livewire\Admin\ProductForm::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.products.create');

Route::get('/admin/products/{product}/edit', \App\Livewire\Admin\ProductForm::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.products.edit');

Route::get('/admin/categories', \App\Livewire\Admin\CategoryList::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.categories');

Route::get('/admin/categories/create', \App\Livewire\Admin\CategoryForm::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.categories.create');

Route::get('/admin/categories/{category}/edit', \App\Livewire\Admin\CategoryForm::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.categories.edit');

Route::get('/test-before-products', function () {
    return '<h1>Test before products works!</h1>';
});

Route::get('/products', \App\Livewire\ProductListingCached::class)
    ->name('products');

Route::get('/products/{product}', \App\Livewire\ProductDetail::class)
    ->name('products.show')
    ->middleware(['cache.headers:public;max_age=3600']);

Route::get('/bikes/{manufacturer}', \App\Livewire\BikeManufacturerListing::class)
    ->name('bikes.manufacturer');

Route::get('/bikes/{manufacturer}/{model}', \App\Livewire\BikeModelListing::class)
    ->name('bikes.model');

Route::get('/bikes/{manufacturer}/{model}/{year}', \App\Livewire\BikeProductListing::class)
    ->name('bikes.show');

Route::get('/checkout', \App\Livewire\Checkout::class)
    ->name('checkout');

Route::get('/checkout/success/{order}', \App\Livewire\CheckoutSuccess::class)
    ->name('checkout.success');

require __DIR__.'/settings.php';
