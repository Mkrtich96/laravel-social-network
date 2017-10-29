<header class="header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light header-navbar">
            <a class="navbar-brand" href="#">{{ $data['name'] }}</a>
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
                        <input class="get-id" type="hidden" data-id="{{ $data['id'] }}">
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/gallery') }}"  class="nav-link text-left">Gallery</a>
                    </li>
                    <li class="nav-item dropdown">
                        @if(count($data->readNotifications) > 0 && !empty($replyFollowers))
                            <a class="nav-link dropdown-toggle notification"
                               id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                Notification <span
                                        class="badge badge-danger">{{ count($data->readNotifications) }}</span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                @foreach($replyFollowers['message'] as $id => $value)
                                    <li class="dropdown-item text-primary form-group header-request">
                                        {{ $replyFollowers['message'][$id] }}
                                        <a data-id="{{ $replyFollowers['follower'][$id] }}"
                                            class="fa fa-check text-right check"></a>
                                        <a data-id="{{ $replyFollowers['follower'][$id] }}"
                                           class="fa fa-times text-right cancel"></a>
                                    </li>
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
