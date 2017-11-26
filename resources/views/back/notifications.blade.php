@extends('back.admin')


@section('container')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Notifications</a>
                </li>
                <li class="breadcrumb-item active">Notifications</li>
            </ol>
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Notifications</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Price $</th>
                                <th>Created</th>
                                <th>Decision</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($notifications))
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->data['user_name'] }}</td>
                                        <td>{{ $notification->data['product_name'] }}</td>
                                        <td>{{ $notification->data['product_description'] }}</td>
                                        <td>$ {{ pointingPrice($notification->data['product_price']) }}</td>
                                        <td>{{ $notification->data['product_created_at'] }}</td>
                                        <td>
                                            <form action="{{ route('admin.approve') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="user" value="{{ $notification->data['user_id'] }}">
                                                <input type="hidden" name="product" value="{{ $notification->data['product_id'] }}">
                                                <button class="badge badge-success accept-product" type="submit">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.denied') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="user" value="{{ $notification->data['user_id'] }}">
                                                <input type="hidden" name="product" value="{{ $notification->data['product_id'] }}">
                                                <button class="badge badge-danger cancel-product" type="submit">Denied</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



@endsection