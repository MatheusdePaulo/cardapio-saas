<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function cartKey(Restaurant $restaurant, string $orderType, ?int $tableNumber): string
    {
        return "cart:{$restaurant->id}:{$orderType}:" . ($tableNumber ?? 'none');
    }

    public function add(Restaurant $restaurant, Request $request)
    {
        $orderType = $request->query('type', 'delivery');
        $tableNumber = $request->query('mesa');

        $productId = (int) $request->input('product_id');
        $qty = max(1, (int) $request->input('qty', 1));

        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->findOrFail($productId);

        $key = $this->cartKey($restaurant, $orderType, $tableNumber ? (int)$tableNumber : null);
        $cart = session()->get($key, []);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price_cents' => $product->price_cents,
            'quantity' => ($cart[$product->id]['quantity'] ?? 0) + $qty,
        ];

        session()->put($key, $cart);

        return redirect()->back()->with('ok', 'Adicionado ao carrinho');
    }

    public function show(Restaurant $restaurant, Request $request)
    {
        $orderType = $request->query('type', 'delivery');
        $tableNumber = $request->query('mesa');

        $key = $this->cartKey($restaurant, $orderType, $tableNumber ? (int)$tableNumber : null);
        $cart = session()->get($key, []);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['unit_price_cents'] * $item['quantity'];
        }

        return view('public.cart', [
            'restaurant' => $restaurant,
            'orderType' => $orderType,
            'tableNumber' => $tableNumber,
            'cart' => $cart,
            'subtotalCents' => $subtotal,
        ]);
    }

    public function update(Restaurant $restaurant, Request $request)
    {
        $orderType = $request->query('type', 'delivery');
        $tableNumber = $request->query('mesa');

        $key = $this->cartKey($restaurant, $orderType, $tableNumber ? (int)$tableNumber : null);
        $cart = session()->get($key, []);

        $quantities = $request->input('qty', []);
        foreach ($quantities as $productId => $qty) {
            $productId = (int) $productId;
            $qty = (int) $qty;

            if (!isset($cart[$productId])) continue;

            if ($qty <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $qty;
            }
        }

        session()->put($key, $cart);

        return redirect()->route('public.cart', $restaurant, array_filter([
            'type' => $orderType,
            'mesa' => $tableNumber,
        ]));
    }

    public function checkout(Restaurant $restaurant, Request $request)
    {
        $orderType = $request->query('type', 'delivery');
        $tableNumber = $request->query('mesa');

        $key = $this->cartKey($restaurant, $orderType, $tableNumber ? (int)$tableNumber : null);
        $cart = session()->get($key, []);

        if (empty($cart)) {
            return redirect()->route('public.menu', $restaurant, array_filter([
                'type' => $orderType,
                'mesa' => $tableNumber,
            ]))->with('err', 'Carrinho vazio');
        }

        // valida mesa quando local
        $tableId = null;
        if ($orderType === 'local') {
            $table = $restaurant->tables()
                ->where('number', (int)$tableNumber)
                ->where('is_active', true)
                ->firstOrFail();
            $tableId = $table->id;
        }

        // dados básicos (por enquanto simples)
        $customerName = $request->input('customer_name');
        $customerPhone = $request->input('customer_phone');
        $deliveryAddress = $request->input('delivery_address');

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['unit_price_cents'] * $item['quantity'];
        }

        $order = Order::create([
            'restaurant_id' => $restaurant->id,
            'table_id' => $tableId,
            'order_type' => $orderType,
            'status' => 'new',
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'delivery_address' => $deliveryAddress,
            'subtotal_cents' => $subtotal,
            'total_cents' => $subtotal,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'unit_price_cents' => $item['unit_price_cents'],
                'quantity' => $item['quantity'],
                'total_cents' => $item['unit_price_cents'] * $item['quantity'],
            ]);
        }

        // limpa carrinho
        session()->forget($key);

        return redirect()->route('public.order.show', ['token' => $order->public_token]);
    }
}
