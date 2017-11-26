@extends('front.layout.layout')


@section('content')
    <div class="container mt-2">

        @if(session('error'))

            <div class="alert alert-danger"> {{ session('error') }} </div>
        @elseif(session('success'))

            <div class="alert alert-success"> {{ session('success') }} </div>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif


        @if(is_null($auth->stripe_account_id))

            <blockquote class="blockquote text-center mt-5">
                <p class="mb-0 text-primary">For selling products you must connect your stripe account to platform.</p>
                <a href="{{ $connect_url }}" class="stripe-connect"><span>Connect with Stripe</span></a>
            </blockquote>

        @else

            <a class="btn btn-success text-light" data-toggle="modal" data-target=".bd-add-product-modal-lg">Add Product</a>

            <form class="float-right" action="{{ route('disconnect.stripe') }}" method="post">
                {{ csrf_field() }}
                <button class="stripe-connect mt-0" type="submit"> <span>Disconnect Stripe account</span></button>
            </form>
        
            @isset($products)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-3 productbox">
                            <div class="producttitle">{{ $product->name }}</div>
                            <div class="producttitle">{{ $product->description }}</div>
                        </div>
                    @endforeach
                </div>
            @endisset
        @endif
    </div>
    @include('front.profile.add_product')
@endsection
