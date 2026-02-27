<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>{{ $restaurant->name }}</title>
</head>
<body style="font-family: Arial; max-width: 500px; margin: 40px auto;">

<h1>{{ $restaurant->name }}</h1>
<p>Como você deseja pedir?</p>

<div style="display:flex; gap:15px;">
    <a href="{{ route('public.menu', $restaurant) }}?type=local"
       style="padding:12px 16px; border:1px solid #000; border-radius:10px; text-decoration:none;">
        🍽️ Consumir no local
    </a>

    <a href="{{ route('public.menu', $restaurant) }}?type=delivery"
       style="padding:12px 16px; border:1px solid #000; border-radius:10px; text-decoration:none;">
        🚚 Delivery / Retirada
    </a>
</div>

</body>
</html>
