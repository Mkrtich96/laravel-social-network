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
                                <img src="{{ $follower['avatar'] }}" class="rounded-circle followers-avatar">
                                <a class="open-message text-primary" data-id="{{ $follower['id'] }}">{{ $follower['name'] }}</a>
                                <a class="btn btn-secondary float-right unfollow" data-id="{{ $follower['id'] }}">Unfollow</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
