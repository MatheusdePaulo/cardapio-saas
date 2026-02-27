<h1>{{ $restaurant->name }}</h1>

<p>Tipo do pedido: {{ $orderType }}</p>

@if($tableNumber)
    <p>Mesa: {{ $tableNumber }}</p>
@endif

<p>Aqui vai entrar o cardápio.</p>
