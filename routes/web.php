<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

Route::get('/products', \App\Livewire\ProductListing::class)
    ->name('products');

Route::get('/products/{product}', \App\Livewire\ProductDetail::class)
    ->name('products.show');

Route::get('/checkout', \App\Livewire\Checkout::class)
    ->name('checkout');

Route::get('/checkout/success/{order}', \App\Livewire\CheckoutSuccess::class)
    ->name('checkout.success');

require __DIR__.'/settings.php';
