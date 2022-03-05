<!DOCTYPE html>
<html lang="en">

<head>
    <title>Harvey</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('pics/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://kit.fontawesome.com/0dd4ecd465.js" crossorigin="anonymous"></script>
</head>

<body>
    @include('partials.flash-messages')

    @yield('content')

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
