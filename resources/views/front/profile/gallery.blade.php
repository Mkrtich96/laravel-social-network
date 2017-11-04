@extends('front.layout.layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="{{ url('/') }}">Back</a>
            </div>
        </div>
    </div>
    <div class="container">
        @if (session('status_200'))
            <div class="alert alert-success text-center">
                {{ session('status_200') }}
            </div>
        @elseif(session('status_404'))
            <div class="alert alert-danger text-center">
                {{ session('status_400') }}
            </div>
        @endif
        <div class="row">
            <div class="col">
                <form action="{{ route('gallery.store') }}" method="post" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-success">Upload</button>
                    <input type="file" name="gallery[]" multiple>
                    {{ csrf_field() }}
                </form>
            </div>
        </div><br>
        <div class="row">
            @if(isset($data))
                @foreach($data as $image)
                    <div class="col-3 images">
                        <div class="card" style="width: 15rem;">
                            <span class="delete-img fa fa-trash-o" data-id="{{ $image['id'] }}"></span>
                            <img class="card-img-top gallery-img" src="{{ asset('images/' . $image['user_id'] . "/gallery/" . $image['image']) }}" >
                            <div class="card-body">
                                <form action="{{ url('/gallery/' . $image['id']) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input name="_method" value="PUT" type="hidden">
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
