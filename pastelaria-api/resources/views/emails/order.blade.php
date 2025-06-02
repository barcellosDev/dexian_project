<h1>Pedido #{{ $order->id }}</h1>

<p>Obrigado pela sua compra. Aqui estão seus pastéis:</p>

<ul>
    @foreach ($order->products as $product)
        <li>{{ $product->name }} — R${{ $product->price }}</li>
    @endforeach
</ul>

<p>Total: R${{ $order->products->sum('price') }}</p>
