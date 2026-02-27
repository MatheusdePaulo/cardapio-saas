<h1>{{ $restaurant->name }}</h1>
<p>Você está na Mesa {{ $table->number }}</p>

<a href="{{ route('public.menu', $restaurant) }}?type=local&mesa={{ $table->number }}">
    Ver Cardápio
</a>
