<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Github account</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{route('home')}}">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="{{ route('logout') }}" method='post'>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="form-control mr-sm-2">Logout</button>
        </form>
    </div>
</nav>
<div class="container-fluid">
    <div class="card" style="width: 20rem;">
        <img class="card-img-top" src="{{ $data['avatar'] }}">
        <div class="card-body">
            <h3 class="card-text">{{ $data['name'] }}</h3>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <button class="btn btn-success create-repo" data-token="{{ csrf_token() }}" data-toggle="modal" data-target=".bd-example-modal-lg">Create Repository
            </button>
            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('profile.store') }}" method="post">
                                @if($errors->any())
                                <div class="form-group">
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="name">Repository Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ old('reponame') }}" name="reponame">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" id="description" {{ old('description') }} name="description">
                                    {{ csrf_field() }}
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="public" value="public">
                                        Public
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="public" value="private">
                                        Private
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="readme">
                                        Initialize this repository with a README.
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-success">Create repository</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(count($repos) > 0)
        @foreach($repos['name'] as $key => $value)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $repos['name'][$key] }}</h4>
                    <a href="{{ $repos['url'][$key] }}" class="btn btn-primary" target="_blank">View Repository</a>
                    <div class="input-group">
                        <input type="text" class="form-control copy-val" value="{{ $repos['clone'][$key] }}"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <button type="button" class="input-group-addon clone-copy" id="basic-addon2"><i
                                    class="fa fa-github" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
