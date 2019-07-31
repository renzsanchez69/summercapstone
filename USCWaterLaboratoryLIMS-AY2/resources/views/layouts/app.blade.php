<!DOCTYPE html>
<html lang="en">
<head>
    <title>Capstone</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="/css/app.css" />
    <link rel="stylesheet" href="/css/datatables.min.css" />
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/capstone.css" />
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/datatables.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ 'Laboratory Information Management System' }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->username }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); 
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div id="content">
        <!--nav class="nav nav-pills flex-column flex-sm-row">
          <a class="flex-sm-fill text-sm-center nav-link" href="/analyst/notification">Notification</a>
          <a class="flex-sm-fill text-sm-center nav-link" href="/analyst/inventory">Inventory</a>
          <button class="flex-sm-fill text-sm-center nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Station</button> 
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              @foreach(Session::get('stations') as $station)
                <a class="flex-sm-fill text-sm-center nav-link" href="/analyst/sample">{{ $station->stationname }}</a>
              @endforeach
          </div>
        </nav-->
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" href="/analyst/notification">Notification</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/analyst/inventory">Inventory</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Station</a>
            <div class="dropdown-menu">
                @foreach(Session::get('stations') as $station)
                    <a class="dropdown-item" href="/analyst/sample/station/{{ $station->stationId }}">{{ $station->stationName }}</a>
                @endforeach
          </li>
        </ul>
        @yield('content')
        <div class="footer">
          <div class="container">
            <a href="https://mdbootstrap.com/education/bootstrap/">USC Chemistry 2018</a>
          </div>
        </div>
    </div>
</body>
</html>
