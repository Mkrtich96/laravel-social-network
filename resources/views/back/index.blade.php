@extends('back.admin')


@section('container')

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">My Dashboard</li>
            </ol>
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Orders</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>Product</th>
                                <th>Created</th>
                            </tr>
                            </thead>
                            <tbody>
                                @isset($orders)
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->email }}</td>
                                            <td>{{ $order->product }}</td>
                                            <td>{{ parseCreatedAt($order->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection