<div class="col-md-3">
    <div class="card" style="width: 14rem;">
        <img class='card-img-top' src="{{ $avatar }}">
        <div class="card-body">
            <p class="card-text">{{ $data['name'] }}</p>
        </div>
    </div>
    <div class="form-group">
        <form action="{{ route('profile.update',$data['id']) }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <p>
                <input type="file" name="avatar">
            </p>
            <p>
                <button class="btn btn-primary" type="submit">Update</button>
            </p>
        </form>
    </div>
</div>