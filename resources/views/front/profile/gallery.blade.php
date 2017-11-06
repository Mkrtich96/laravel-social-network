@extends('front.layout.layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ url('/') }}">Back</a>
            </div>
        </div>
    </div>
    <input type="hidden" class="get-id" data-id="{{ get_auth('id') }}">
    <div class="container">
        @if( $errors->any() )
            @foreach($errors->all() as $error)
                <div class="alert alert-danger text-center">
                    <strong>{{ $error }}</strong>
                </div>
            @endforeach
        @endif
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @elseif(session('fail'))
            <div class="alert alert-danger text-center">
                <strong>{{ session('fail') }}</strong>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <form action="{{ route('gallery.store') }}" method="post" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-success">Upload</button>
                    <input type="file" name="gallery[]" multiple="multiple">
                    {{ csrf_field() }}
                </form>
            </div>
        </div><br>
        <div class="row">
            @if(isset($gallery))
                @foreach($gallery as $image)
                    <div class="col-3 images" data-id="{{ $image['id'] }}">
                        <div class="card" style="width: 15rem;">
                            <span class="delete-img fa fa-trash-o" data-id="{{ $image['id'] }}"></span>
                            <img class="card-img-top gallery-img" src="{{ asset('images/' . $image['user_id'] . "/gallery/" . $image['image']) }}" >
                            <div class="card-body">
                                <form action="{{ route('make.profile.photo') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="image_id" value="{{ $image['id'] }}">
                                    <button type="submit" class="btn btn-outline-primary make-profile-pic">Make Profile Photo</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
