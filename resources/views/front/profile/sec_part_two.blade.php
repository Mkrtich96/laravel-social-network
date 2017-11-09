<div class="col-12 col-sm-12 col-md-7 section-2nd">
    <div class="alert alert-danger alert-post-error" role="alert">
        <strong>Invalid!</strong> Please type post, then send.
    </div>
    <div class="alert alert-success alert-post-success" role="alert">
        <strong>Complete!</strong> Posted successfully.
    </div>
    @if(session('fail'))
        <div class="alert alert-danger">
            {{ session('fail') }}
        </div>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="align-items-center">
        <h3> Make Post <i class="fa fa-pencil" aria-hidden="true"></i></h3>
        <form class="form-post">
            <div class="form-row">
                <div class="col-9">
                    <textarea class="form-control post-text" aria-describedby="emailHelp" placeholder="What's on your mind, {{ $auth['name'] }}?"></textarea>
                </div>
                <div class="col">
                    <div class="form-check">
                        <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                            <input type="checkbox" class="checkbox custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Private</span>
                        </label>
                    </div>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary rounded-left h-100 w-75 post">Post</button>
                </div>
            </div>

        </form>
        <div class="cards">
            @if( isset($posts) )
                @foreach( $posts as $post )
                    <div class="parent-card users-res card col-12 col-sm-12">
                        <div class="card-body">
                            <div class="card-text">
                                <img src="{{ $user_avatar }}" class="rounded-circle followers-avatar float-left">
                                <h5 class="card-title">{{ $auth['name'] }}</h5>
                                <small class="form-text text-muted">{{ parseCreatedAt($post->date) }}</small>
                            </div>
                            <div class="card-text mt-3">
                                <p class="text-justify" data-id="{{ $post->id }}">
                                    {{ $post->text }}
                                </p>
                            </div>
                            <div class="card-text float-right w-75 comments-body">
                                @foreach($post->comments as $comment)
                                    <div class="card">
                                        <div class="card-body p-1">
                                            <h5 class="card-title">
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
                                <div class="input-group input-group-sm mt-2 apply-comment">
                                    <input type="text" class="rounded-0 form-control" placeholder="Comment.." aria-describedby="sizing-addon2" data-id="{{ $post->id }}" >
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="card-text mt-3">

                                <a class="btn comment badge badge-primary text-light float-right"  data-id="{{ $post->id }}" >
                                    Comments
                                    <span class="badge badge-light">{{ count($post->comments) }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
