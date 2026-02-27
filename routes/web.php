<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantPublicController;

Route::get('/{restaurant}', [RestaurantPublicController::class, 'entry'])
    ->name('public.entry');

Route::get('/{restaurant}/mesa/{number}', [RestaurantPublicController::class, 'table'])
    ->whereNumber('number')
    ->name('public.table');

Route::get('/{restaurant}/menu', [RestaurantPublicController::class, 'menu'])
    ->name('public.menu');
