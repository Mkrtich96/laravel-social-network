@extends('front.layout.layout')
@section('content')
    @if( $auth['provider'] == 'github' )
        @include('front.provider.github')
    @else
        @include('front.profile.profile')
    @endif
@endsection
