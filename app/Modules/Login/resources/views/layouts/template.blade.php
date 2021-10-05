<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <title>@yield("title")</title>
    @include('Login::components.css')
</head>
<body class="pace-top bg-white">
    @yield('content')

    @include('Login::components.js')
</body>
</html>