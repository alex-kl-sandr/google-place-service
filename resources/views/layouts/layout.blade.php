<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body class="bg-dark">
        @include('layouts.header')
        <div id="app" class="container">
            @yield('content')
        </div>
        @include('layouts.footer')

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>