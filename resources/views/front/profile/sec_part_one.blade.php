<div class="col-md-2">
    <div class="card" style="width: 13rem;">
        <img class='card-img-top' src="{{ $avatar }}">

    </div>
    <div class="form-group">
        <form action="{{ route('user.avatar') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <p>
                <input type="file" name="avatar" class="w-100">
            </p>
            <p>
                <button class="btn btn-primary" type="submit">Update</button>
            </p>
        </form>
    </div>
</div>