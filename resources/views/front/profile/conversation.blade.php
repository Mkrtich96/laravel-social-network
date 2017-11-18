@extends('front.layout.layout')

@section('content')
<div class="container">
    <div class="row">
        {{-- Group view --}}
        @if(isset($conversation))
            @include('front.profile.group')
        @endif
    </div>
</div>
@endsection