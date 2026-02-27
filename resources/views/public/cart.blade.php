<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }} - Carrinho</title>
</head>
<body style="font-family: Arial; max-width: 720px; margin: 40px auto;">

<h1>🛒 Carrinho - {{ $restaurant->name }}</h1>

<p>
    Tipo: <strong>{{ $orderType }}</strong>
    @if($tableNumber) | Mesa: <strong>{{ $tableNumber }}</strong> @endif
</p>

@if(session('ok'))
    <p style="color:green;">{{ session('ok') }}</p>
@endif
@if(session('err'))
    <p style="color:red;">{{ session('err') }}</p>
@endif

<form method="POST" action="{{ route('public.cart.update', $restaurant) }}?type={{ $orderType }}@if($tableNumber)&mesa={{ $tableNumber }}@endif">
    @csrf

    @forelse($cart as $item)
        <div style="padding:10px 0; border-bottom:1px solid #eee;">
            <strong>{{ $item['name'] }}</strong><br>
            <small>R$ {{ number_format($item['unit_price_cents']/100, 2, ',', '.') }}</small>

            <div style="margin-top:8px;">
                Qtd:
                <input type="number" min="0" name="qty[{{ $item['product_id'] }}]" value="{{ $item['quantity'] }}" style="width:80px;">
                <small style="color:#777;">(0 remove)</small>
            </div>
        </div>
    @empty
        <p>Carrinho vazio.</p>
    @endforelse

    <p style="margin-top:16px;">
        <strong>Subtotal:</strong> R$ {{ number_format($subtotalCents/100, 2, ',', '.') }}
    </p>

    <button type="submit" style="padding:10px 14px;">Atualizar carrinho</button>
</form>

<hr style="margin:20px 0;">

<h2>Finalizar pedido</h2>

<form method="POST" action="{{ route('public.checkout', $restaurant) }}?type={{ $orderType }}@if($tableNumber)&mesa={{ $tableNumber }}@endif">
    @csrf

    @if($orderType !== 'local')
        <div style="margin-bottom:10px;">
            <label>Seu nome</label><br>
            <input name="customer_name" style="width:100%; padding:8px;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Telefone</label><br>
            <input name="customer_phone" style="width:100%; padding:8px;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Endereço</label><br>
            <input name="delivery_address" style="width:100%; padding:8px;">
        </div>
    @endif

    <button type="submit" style="padding:12px 16px;">Confirmar pedido</button>
</form>

<p style="margin-top:16px;">
    <a href="{{ route('public.menu', $restaurant) }}?type={{ $orderType }}@if($tableNumber)&mesa={{ $tableNumber }}@endif">Voltar ao cardápio</a>
</p>

</body>
</html>
