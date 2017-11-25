@extends('front.layout.layout')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{asset('bootstrap4/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/font-awesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/user.css') }}">
    <link rel="stylesheet" href="{{asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{asset('css/gallery.css') }}">
    <link rel="stylesheet" href="{{asset('css/stripe.css') }}">
@endsection


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
        
            @if(isset($products))
                @foreach($products as $product)
    
    
    
                @endforeach
            @endif
        @endif
    </div>
    @include('front.profile.add_product')
@endsection

@section('foot')
    <script src="{{ asset('js/jquery.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
    </script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('js/message.js') }}"></script>
@endsection