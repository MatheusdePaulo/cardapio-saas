<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedido</title>
</head>
<body style="font-family: Arial; max-width: 720px; margin: 40px auto;">

<h1>✅ Pedido registrado</h1>

<p>Status: <strong>{{ $order->status }}</strong></p>
<p>Tipo: <strong>{{ $order->order_type }}</strong></p>

@if($order->table_id)
    <p>Mesa vinculada: <strong>{{ optional($order->table)->number }}</strong></p>
@endif

<hr>

@foreach($order->items as $item)
    <div style="padding:10px 0; border-bottom:1px solid #eee;">
        <strong>{{ $item->name }}</strong><br>
        <small>
            {{ $item->quantity }}x —
            R$ {{ number_format($item->unit_price_cents/100, 2, ',', '.') }}
        </small>
    </div>
@endforeach

<p style="margin-top:16px;">
    <strong>Total:</strong> R$ {{ number_format($order->total_cents/100, 2, ',', '.') }}
</p>

<p style="margin-top:16px; color:#555;">
    Link deste pedido: <code>{{ url('/pedido/'.$order->public_token) }}</code>
</p>

</body>
</html>
