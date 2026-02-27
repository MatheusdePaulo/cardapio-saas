<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderPublicController extends Controller
{
    public function show(string $token)
    {
        $order = Order::where('public_token', $token)
            ->with('items')
            ->firstOrFail();

        return view('public.order', compact('order'));
    }
}
