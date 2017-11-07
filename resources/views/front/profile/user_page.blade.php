@extends('front.layout.layout')
@section('content')
    <header class="header-user">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('/user/' . $user->id) }}">{{ $user->name }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                    <li>
                        <input class="get-id" type="hidden" data-id="{{ $auth_id }}">
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <a class="nav-link sign-up text-center" href="{{ url('/') }}">Back to profile</a>
                </form>
            </div>
        </nav>
    </header>
    <section class="section-1-user">
        <div class="container-fluid">
            <div class="row">
                <div class="col-1 col-lg-3">
                    <div class="card">

                        <img src="{{ $user_avatar }}" class="img-thumbnail card-img-top">

                        <div class="card-body">
                            {!! $followButton !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="cards">
                            @if( isset($posts) )
                                @foreach( $posts as $post )
                                    <div class="parent-card users-res card col-12 col-sm-12">
                                        <div class="card-body">
                                            <div class="card-text">
                                                <img src="{{ asset('images/' . $user->id . '/' . $user->avatar) }}" class="rounded-circle followers-avatar float-left">
                                                <h5 class="card-title">{{ $user->name }}</h5>
                                                <small class="form-text text-muted">{{ $post['date'] }}</small>
                                            </div>
                                            <div class="card-text mt-3">
                                                <p class="text-justify">
                                                    {{ $post['text'] }}
                                                </p>
                                            </div>
                                            <div class="card-text float-right w-75 comments-body">
                                                <div class="card">
                                                    <div class="card-body p-1">
                                                        <h5 class="card-title">
                                                            Hakob
                                                        </h5>
                                                            Hello vasil
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm mt-2 apply-comment">
                                                    <input type="text" class="rounded-0 form-control" placeholder="Comment.." aria-describedby="sizing-addon2" data-id="{{ $post['id'] }}" data-user="{{ $user->id }}">
                                                </div>
                                            </div>

                                            <div class="clearfix"></div>

                                            <div class="card-text mt-2">
                                                <a class="btn comment badge badge-primary text-light float-right"  data-id="{{ $post['id'] }}" data-user="{{ $user->id }}" >Comments</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                    </div>
                </div>
                @include('front.profile.sec_part_three')
            </div>
        </div>
    </section>

@endsection