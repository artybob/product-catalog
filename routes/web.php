<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/product/{id}', function ($id) {
    return Inertia::render('Product/Show', ['id' => $id]);
})->name('product.show');

Route::get('/admin/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin/products', function () {
        return Inertia::render('Admin/Products/Index');
    })->name('admin.products');

    Route::get('/admin/products/create', function () {
        return Inertia::render('Admin/Products/Create');
    })->name('admin.products.create');

    Route::get('/admin/products/{id}/edit', function ($id) {
        return Inertia::render('Admin/Products/Edit', ['id' => $id]);
    })->name('admin.products.edit');
});
