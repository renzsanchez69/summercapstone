<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>USC Water Laboratory</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('fontawesome/font-awesome.min.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/gif" href="{{ asset('favicon.ico') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('fontawesome/css/fontawesome.min.css') }}"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @include('custom_style')

</head>
<style>
     body {
       background: url(img/bkg5.jpg) no-repeat center center fixed;
       background-size: 100% 100%;
       height: 100%;
       position: absolute;
       width: 100%;
     }
</style>
<body>

        <main class="py-4">
            @yield('content')
        </main>
</body>

</html>
