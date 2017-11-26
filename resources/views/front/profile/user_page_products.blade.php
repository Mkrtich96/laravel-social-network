@extends('front.layout.layout')

@section('content')
<div class="container">
    <div class="row">
        @isset($products)
            @foreach($products as $product)
                <div class="col-3 productbox">
                    <form action="{{ route('pay', $product->id) }}" method="POST">
                        {{ csrf_field() }}

                        <div class="producttitle">{{ $product->name }}</div>
                        <div class="producttitle">{{ $product->description }}</div>
                        <div class="productprice">
                            <div class="pull-right">
                                <script src="https://checkout.stripe.com/checkout.js"
                                        class="stripe-button"
                                        data-key="{{ env('STRIPE_KEY') }}"
                                        data-amount="{{ $product->price }}"
                                        data-name="Stripe.com"
                                        data-description="{{ $product->description }}"
                                        data-locale="auto"
                                        data-currency="usd">
                                </script>
                            </div>
                            <div class="pricetext">$ {{ pointingPrice($product->price) }}</div>
                        </div>
                    </form>
                </div>
            @endforeach
        @endisset
    </div>
</div>

@endsection