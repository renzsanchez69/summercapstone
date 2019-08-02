<!-- NAVIGATION -->
<nav id="navigation">
  <!-- container -->
  <div class="container">
    <!-- responsive-nav -->
    <div id="responsive-nav">
      <!-- NAV -->
      <ul class="main-nav nav navbar-nav">
        @if (Auth::check())

        <li><a href="{{ route('about') }}">About Us</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
        <li><a href="{{ route('categories') }}">Categories</a></li>
        <li><a href="{{ route('profile') }}">User Profile</a></li>



        @if (Auth::user()->isSeller())
        <li><a href="{{ route('sellerHistory') }}">Seller History</a></li>
        <li><a href="{{ route('sellersProfile', Auth::user()->seller->id)  }}">Seller Profile</a></li>
        <li><a href="{{ route('productsCreateForm') }}">Sell an Item</a></li>

        @endif
        @if (Auth::user()->isAdmin())
        <li><a href="{{ route('admin') }}">Admin</a></li>
        @endif
        @endif





      </ul>
      <!-- /NAV -->
    </div>
    <!-- /responsive-nav -->
  </div>
  <!-- /container -->
</nav>
<!-- /NAVIGATION -->