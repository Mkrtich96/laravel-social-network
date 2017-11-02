<div class="col-md-2">
    <div class="card" style="width: 13rem;">
        <img class='card-img-top' src="{{ $avatar }}">

    </div>
    <div class="form-group">
        <form action="{{ route('profile.update',$data['id']) }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <p>
                <input type="file" name="avatar" style="width:100%">
            </p>
            <p>
                <button class="btn btn-primary" type="submit">Update</button>
            </p>
        </form>
    </div>
</div>