<?php

use App\Users;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{csrf_token()}}" />
  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

  <!-- Bootstrap -->
  <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

  <!-- Slick -->
  <link type="text/css" rel="stylesheet" href="{{ asset('css/slick.css') }}" />
  <link type="text/css" rel="stylesheet" href="{{ asset('css/slick-theme.css') }}" />

  <!-- nouislider -->
  <link type="text/css" rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}" />

  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

  <!-- Custom stlylesheet -->
  <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Niugini-hub</title>
  <script src="{{ asset('js/jquery.min.js') }}"></script>
</head>

<body>
  <!-- HEADER -->
  <header>
    <!-- TOP HEADER -->
    <div id="top-header">
      <div class="container">
        <ul class="header-links pull-left">
          <li><a href="/">Home</li>
        </ul>
        <ul class="header-links pull-right">
          <!-- Authentication Links -->
          @guest
          <li><a href="{{ route('login') }}">Login</a></li>
          @if (Route::has('register'))
          <li><a href="{{ route('register') }}">Register</a></li>
          @endif
          @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              <i class="fa fa-user-o"></i>{{ ucfirst(Auth::user()->name) }}&nbsp<span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background-color: #15161D;">
              <a class="dropdown-item" href="{{ route('profile') }}" style="padding-left: 1rem; padding-right: 1rem;">
                Profile
              </a>
              <a class="dropdown-item" href="{{ route('userHistory') }}" style="padding-left: 1rem; padding-right: 1rem;">
                Order History
              </a>
              <a class="dropdown-item" href="{{ route('updatePasswordForm') }}" style="padding-left: 1rem; padding-right: 1rem;">
                Update Password
              </a>
              <a class="dropdown-item" href="{{ route('deactivateForm') }}" style="padding-left: 1rem; padding-right: 1rem;">
                Deactivate Account
              </a>
              <a class="dropdown-item" href="{{ route('logout') }}" style="padding-left: 1rem; padding-right: 1rem;" onclick="event.preventDefault();
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
    <!-- /TOP HEADER -->

  </header>
  <!-- /HEADER -->

  @yield('content')


  <!-- NEWSLETTER -->
  <div id="newsletter" class="section">
    <!-- container -->
    <div class="container">

    </div>
    <!-- /container -->
  </div>
  <!-- /NEWSLETTER -->

  <!-- FOOTER -->
  <footer id="footer">
    <!-- top footer -->
    <div class="section">
      <!-- container -->
      <div class="container">
        <!-- row -->
        <div class="row">
          <div class="col-md-3 col-xs-6">
            <div class="footer">
              <h3 class="footer-title">About Us</h3>
              <p>Online Ordering System called Niugini Hub for the people of Port Moresby in Papua New Guinea.<p>
                  <ul class="footer-links">
                    <li><a href="#"><i class="fa fa-map-marker"></i>Port Moresby in Papua New Guinea.</a></li>
                    <li><a href="#"><i class="fa fa-phone"></i>325 6163</a></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i>niuginihub@gmail.com </a></li>
                  </ul>
            </div>
          </div>


          <div class="col-md-3 col-xs-6">
            <div class="footer">
              <h3 class="footer-title">Information</h3>
              <ul class="footer-links">
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
              </ul>
            </div>
          </div>


          <div class="col-md-3 col-xs-6">
            <div class="footer">
              <h3 class="footer-title">Service</h3>
              <ul class="footer-links">
                <li><a href="{{ route('profile') }}">My Account</a></li>
                <li><a href="{{ route('cart') }}">View Cart</a></li>
              </ul>
            </div>
          </div>
        </div>
        <!-- /row -->
      </div>
      <!-- /container -->
    </div>
    <!-- /top footer -->

    <!-- bottom footer -->
    <div id="bottom-footer" class="section">
      <div class="container">
        <!-- row -->
        <div class="row">
          <div class="col-md-12 text-center">
            <ul class="footer-payments">
              <li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
              <li><a href="#"><i class="fa fa-credit-card"></i></a></li>
              <li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
              <li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
              <li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
              <li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
            </ul>
            <span class="copyright">
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              Copyright &copy;<script>
                document.write(new Date().getFullYear());
              </script> All rights reserved | Niugini
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </span>


          </div>
        </div>
        <!-- /row -->
      </div>
      <!-- /container -->
    </div>
    <!-- /bottom footer -->
  </footer>
  <!-- /FOOTER -->

  @yield('modals')

  <!-- jQuery Plugins -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/slick.min.js') }}"></script>
  <script src="{{ asset('js/nouislider.min.js') }}"></script>
  <script src="{{ asset('js/jquery.zoom.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/login.js') }}"></script>
  <script src="{{ asset('js/product.js') }}"></script>
  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  <script src="{{ asset('js/checkout.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}"></script>
  @yield('scripts')
</body>

</html>