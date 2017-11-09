<header class="header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light header-navbar">
            <a class="navbar-brand" href="{{ url('/') }}">{{ $auth['name'] }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2 search-input" type="text"
                           placeholder="Find friends..." aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0 search-btn fa fa-search"></button>
                </form>
                <ul class="navbar-nav mr-auto navbar-list float-right dropdowns">
                    <li class="nav-item">
                        <input class="get-id" type="hidden" data-id="{{ $auth['id'] }}">
                    </li>
                    <li class="nav-item">
                        <a href="{{ route("gallery.show", $auth['id']) }}"  class="nav-link text-left">Gallery</a>
                    </li>
                    <li class="nav-item dropdown">
                        @if(isset($notifications))
                            <a class="nav-link dropdown-toggle notification"
                               id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                Notification <span
                                        class="badge badge-danger">{{ count($notifications) }}</span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                @foreach($notifications as $notify)
                                    @if($notify['system'] == 'follow')
                                    <li class="dropdown-item text-primary form-group header-request">
                                        {{ $notify['follower_name'] }} send follow request
                                        <a data-id="{{ $notify['follower_id'] }}"
                                            class="fa fa-check text-right accept-follow"></a>
                                        <a data-id="{{ $notify['follower_id'] }}"
                                           class="fa fa-times text-right cancel-follow"></a>
                                    </li>
                                    @elseif($notify['system'] == 'comment')
                                        <li class="dropdown-item form-group header-request">
                                            <a href="{{ route('user.guest',$notify['commentator_id']) }}" target="_blank">
                                                {{ $notify['commentator_name'] }}
                                            </a>
                                            applied to your comment.
                                            <a class="comment-seen" href="{{ route('user.guest',$notify['in_user_post']) }}" data-id="{{ $notify['commentator_id'] }}" parent-id="{{ $notify['notifiable_id'] }}" target="_blank">
                                                see.
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <a class="nav-link dropdown-toggle notification" id="navbarDropdownMenuLink"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                Notification <span
                                        class="badge badge-danger"></span>
                            </a>
                        @endif

                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <a href="javascript:;" onclick="parentNode.submit();" class="nav-link sign-up text-center">Logout</a>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
