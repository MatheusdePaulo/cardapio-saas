<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name }} - Cardápio</title>
</head>
<body style="font-family: Arial; max-width: 720px; margin: 40px auto;">

<h1>🍔 {{ $restaurant->name }}</h1>

<p>
    Tipo: <strong>{{ $orderType }}</strong>
    @if($tableNumber) | Mesa: <strong>{{ $tableNumber }}</strong> @endif
</p>

<hr>

@forelse($categories as $category)
    <h2>{{ $category->name }}</h2>

    @forelse($category->products as $product)
        <div style="padding: 10px 0; border-bottom: 1px solid #eee;">
            <strong>{{ $product->name }}</strong>
            <div style="color:#555;">{{ $product->description }}</div>
            <div>R$ {{ number_format($product->price_cents / 100, 2, ',', '.') }}</div>
        </div>
    @empty
        <p style="color:#777;">Sem produtos nesta categoria.</p>
    @endforelse
@empty
    <p>Nenhuma categoria cadastrada ainda.</p>
@endforelse

<p style="margin-top: 24px;">
    <a href="{{ route('public.entry', $restaurant) }}">Voltar</a>
</p>

</body>
</html>
