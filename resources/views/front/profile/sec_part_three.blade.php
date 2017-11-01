<div class="col-12 col-sm-12 col-md-3 section-1nd">
    <div class="col cols">
        <div class="align-items-center">
            <div class="row">
                <p class="user-parameters">Followers</p>
            </div>
            <div class="row">
                <ul class="list-group">
                    @if(isset($followers_list))
                        @foreach($followers_list['name'] as $key => $value)
                            <li class="list-group-item">
                                <img src="{{ $followers_list['avatar'][$key] }}" class="rounded-circle followers-avatar">
                                <a class="open-message text-primary" data-id="{{ $followers_list['id'][$key] }}">{{ $followers_list['name'][$key] }}</a>
                                <a class="btn btn-secondary float-right unfollow" data-id="{{ $followers_list['id'][$key] }}">Unfollow</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
