<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantPublicController extends Controller
{
    public function entry(Restaurant $restaurant)
    {
        abort_unless($restaurant->is_active, 404);

        return view('public.entry', compact('restaurant'));
    }

    public function table(Restaurant $restaurant, int $number)
    {
        abort_unless($restaurant->is_active, 404);

        $table = $restaurant->tables()
            ->where('number', $number)
            ->where('is_active', true)
            ->firstOrFail();

        return view('public.table', compact('restaurant', 'table'));
    }

    public function menu(Restaurant $restaurant, Request $request)
    {
        abort_unless($restaurant->is_active, 404);

        $orderType = $request->query('type', 'delivery');
        $tableNumber = $request->query('mesa');

        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['products' => function ($q) use ($restaurant) {
                $q->where('is_active', true)
                    ->where('restaurant_id', $restaurant->id)
                    ->orderBy('sort_order');
            }])
            ->get();

        return view('public.menu', compact('restaurant', 'orderType', 'tableNumber', 'categories'));
    }
}
