<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>USC Water Laboratory</title>

           <!-- Scripts -->
           <link href="{{ asset('select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>     -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="{{ asset('fontawesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap3.4.0/icons/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('w3css/w3.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontgoogleapi.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/gif" href="{{ asset('favicon.ico') }}" />
   
    
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    

<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>

    <!-- Client Style -->
    @include('layouts/client_homestyle')
   
</head>
<body>
<!-- Navbar (sit on top) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
  <div class="container-fluid w3-bar">
    <div class="row">
      <div class="col-lg-12 text-white cliNavLogo">
    <a class="navbar-brand" href="{{ url('/') }}">
          <img src="/img/logo.png" alt="logo" class="d-inline-block align-top" style="height: 30px; ">
          USC WATER LABORATORY
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
      </div>
    </div>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item nav-link nav-link-ltr active">
          <a class="nav-link" href="{{ url('/') }}"><i class="fa fa-home"></i>Home
              </a>
        </li>
        <li class="nav-item nav-link nav-link-ltr active">
          <a class="nav-link" href="{{ url('/S&R') }}"><i class="fa fa-user"></i>Service & Rates</a>
        </li>
        <li class="nav-item nav-link nav-link-ltr active">
          <a class="nav-link" href="{{ url('/contact') }}"><i class="fa fa-envelope"></i>Contact</a>
        </li>
        <li class="nav-item nav-link nav-link-ltr active">
          <a class="nav-link" href="{{ url('/login') }}"><i class="fa fa-sign-in"></i>Login</a>
        </li>
        <li class="nav-item link">
        <form class="nav-item" method="post" action="{{ route('RIS') }}" > 
          @csrf
          <input type="text" class="SearchRis" placeholder="RIS NUMBER" name="search" oninvalid="this.setCustomValidity('Input your  RIS Number')"  title='RIS NUMBER'  oninput="setCustomValidity('')" required/> 
          <button type="submit" class="btn btn-outline-light btn-md my-0 my-sm-0 ml-2"><i class="fa fa-search"></i></button>
        </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

        <main>
            @yield('content')
            @yield('footer')        
        </main>
        

        <script type="text/javascript">

$(document).ready(function() {
    $('.js-example-responsive').select2();
});
$(document).ready(function () {
    $("#flash-msg").delay(3000).fadeOut("slow");
});
</script>
</body>
</html>
