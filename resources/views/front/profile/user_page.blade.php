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
                    @if(isset($products))
                        <a class="nav-link text-dark" href="{{ route('guest.products',$user->id) }}">Products</a>
                    @else
                        <a class="nav-link" disabled>Products</a>
                    @endif
                    <li>
                        <input class="get-id" type="hidden" data-id="{{ get_auth('id') }}">
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

                        @if( isset($posts) )
                            <div class="cards">
                            @foreach( $posts as $post )
                                <div class="parent-card users-res card col-12 col-sm-12">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <img src="{{ $user_avatar }}" class="rounded-circle followers-avatar float-left">
                                            <h5 class="card-title">{{ $user->name }}</h5>
                                            <small class="form-text text-muted">{{ parseCreatedAt($post->date) }}</small>
                                        </div>
                                        <div class="card-text mt-3">
                                            <p class="text-justify">
                                                {{ $post->text }}
                                            </p>
                                        </div>
                                        <div class="card-text float-right w-75 comments-body">
                                                @foreach($post->comments as $comment)
                                                    <div class="card">
                                                        <div class="card-body p-2">
                                                            <h5 class="card-title" data-id="{{ $comment->user->id }}">
                                                                {{ $comment->user->name }}
                                                            </h5>
                                                            @if(!is_null($comment->parent_id))
                                                                @php
                                                                    $comment_parent = $comment->parent()->with('user')->first();
                                                                    $parent_id = $comment_parent->user->id;
                                                                @endphp

                                                                <a href="{{ url("/user/$parent_id") }}" target="_blank">
                                                                    {{ $comment_parent->user->name }}
                                                                </a>
                                                            @endif
                                                                {{ $comment->comment }}
                                                        </div>
                                                        @if($comment->user->name != $auth->name)
                                                            <a href="" class="reply-comment" data-id="{{ $comment->id }}">Reply</a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            <div class="input-group input-group-sm mt-2 apply-comment" id="comment">
                                                <input type="text" class="rounded-0 form-control send-comment" placeholder="Comment.." aria-describedby="sizing-addon2" data-id="{{ $post->id }}">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="card-text mt-2">
                                            <a class="btn comment badge badge-primary text-light float-right"  data-id="{{ $post->id }}" data-user="{{ $user->id }}" >
                                                Comments
                                                <span class="badge badge-light">{{ count($post->comments) }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @endif
                </div>
                @include('front.profile.sec_part_three')
            </div>
        </div>
    </section>
@endsection
