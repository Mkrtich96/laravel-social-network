@extends('front.layout.layout')
@section('content')
    @if( $data['provider'] == 'github' )
        @include('front.provider.github')
    @else
        @include('front.profile.profile')
    @endif
@endsection
