<div class="col-12 col-sm-12 col-md-3 section-1nd">
    <div class="col cols">
        <div class="align-items-center">
            <div class="row">
                <p class="user-parameters">Followers</p>
            </div>
            <div class="row">
                <ul class="list-group">
                    @if(isset($followers_list))
                        @foreach($followers_list as $follower)
                            <li class="list-group-item">
                                @if($follower->user_id == $auth->id)
                                    <img src="{{ generate_avatar($follower->userRight) }}" class="rounded-circle followers-avatar">
                                    <a class="open-message text-primary" data-id="{{ $follower->userRight->id }}">{{ $follower->userRight->name }}</a>
                                    <a class="btn btn-secondary float-right unfollow" data-id="{{ $follower->userRight->id }}">Unfollow</a>
                                @else
                                    <img src="{{ generate_avatar($follower->userLeft) }}" class="rounded-circle followers-avatar">
                                    <a class="open-message text-primary" data-id="{{ $follower->userLeft->id }}">{{ $follower->userLeft->name }}</a>
                                    <a class="btn btn-secondary float-right unfollow" data-id="{{ $follower->userLeft->id }}">Unfollow</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
