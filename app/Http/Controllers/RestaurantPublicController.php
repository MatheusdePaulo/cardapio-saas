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

        // aqui depois vamos jogar pra tela do cardápio já com mesa selecionada
        return view('public.table', compact('restaurant', 'table'));
    }

    public function menu(Restaurant $restaurant, Request $request)
    {
        abort_unless($restaurant->is_active, 404);

        $orderType = $request->query('type', 'delivery'); // delivery|local
        $tableNumber = $request->query('mesa'); // opcional

        return view('public.menu', compact('restaurant', 'orderType', 'tableNumber'));
    }
}
