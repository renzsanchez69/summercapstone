<title>USC Water Laboratory</title>
<meta charset="UTF-8" />
<link rel="stylesheet" href="{{ asset('css/app.css') }}" />
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/capstone.css') }}" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
<link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />
<link rel="icon" type="image/gif" href="{{ asset('favicon.ico') }}" />
<script src="{{ asset('js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/datatables.min.js')}}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js')}}"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/analyst/notification') }}">
                <img src="/img/logo.png" style="height: 22px;">
                    {{ 'USC WATER LABORATORY' }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                    @if( $notifcount > 0)
                      <a class="nav-link" href="{{ route('analystnotification') }}">{{ __('Notification') }} <b style="color:red">{{ $notifcount }}</b></a>
                    @else
                      <a class="nav-link" href="{{ route('analystnotification') }}">{{ __('Notification') }}</a>
                    @endif
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('analystsamples') }}">{{ __('Samples') }}</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('analystinventory') }}">{{ __('Inventory') }}</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Station
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          @foreach( $stations as $station)
                              <a class="dropdown-item" href="/analyst/sample/station/{{ $station->stationId }}">{{ $station->stationName }}</a>
                          @endforeach
                      </div>
                  </li>
                </ul>
                <ul class="navbar-nav ml-auto" style="float: right;">
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
                                    {{ Auth::user()->username }}</span>
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
<br>
@yield('content')
</div>
