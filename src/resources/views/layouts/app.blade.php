<!DOCTYPE html>
<html lang="en">

<head>
    <title>Harvey</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('pics/favicon.ico') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @include('partials.flash-messages')

    @yield('content')

    <script src="https://kit.fontawesome.com/0dd4ecd465.js" crossorigin="anonymous"></script>
</body>

</html>
