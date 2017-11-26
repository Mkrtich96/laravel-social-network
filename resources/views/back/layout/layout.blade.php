<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('bootstrap4/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/font-awesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/sb-admin.css') }}">
</head>
<body>
@yield('content')
</body>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('js/helpers.js') }}"></script>
</html>