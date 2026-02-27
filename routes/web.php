<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantPublicController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderPublicController;

Route::get('/{restaurant}', [RestaurantPublicController::class, 'entry'])
    ->name('public.entry');

Route::get('/{restaurant}/mesa/{number}', [RestaurantPublicController::class, 'table'])
    ->whereNumber('number')
    ->name('public.table');

Route::get('/{restaurant}/menu', [RestaurantPublicController::class, 'menu'])
    ->name('public.menu');

Route::post('/{restaurant}/cart/add', [CartController::class, 'add'])->name('public.cart.add');
Route::get('/{restaurant}/cart', [CartController::class, 'show'])->name('public.cart');
Route::post('/{restaurant}/cart/update', [CartController::class, 'update'])->name('public.cart.update');
Route::post('/{restaurant}/checkout', [CartController::class, 'checkout'])->name('public.checkout');

Route::get('/pedido/{token}', [OrderPublicController::class, 'show'])
    ->name('public.order.show');
