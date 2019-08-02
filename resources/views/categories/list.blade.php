@extends('layouts.base')

@section('content')

<!-- MAIN HEADER -->
<div id="header">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">

      <!-- SEARCH BAR -->
      <div class="col-md-9">
        <div class="header-search">
          <form method="POST" action="/search">
            @csrf
            <input class="input" placeholder="Search here">
            <button class="search-btn">Search</button>
          </form>
        </div>
      </div>
      <!-- /SEARCH BAR -->

      <!-- ACCOUNT -->
      <div class="col-md-3 clearfix">
        <div class="header-ctn">
        @if (Auth::check())
          <!-- Wishlist -->
          <div>
            <a href="{{ route('home') }}">
              <i class="fa fa-heart-o"></i>
              <span>Your Wishlist</span>
            </a>
          </div>
          <!-- /Wishlist -->

          <!-- Cart -->
          <div>
            <a href="{{ route('home') }}">
              <i class="fa fa-shopping-cart"></i>
              <span>Your Cart</span>
            </a>
          </div>
          <!-- /Cart -->
        @endif
          <!-- Menu Toogle -->
          <div class="menu-toggle">
            <a href="#">
              <i class="fa fa-bars"></i>
              <span>Menu</span>
            </a>
          </div>
          <!-- /Menu Toogle -->
        </div>
      </div>
      <!-- /ACCOUNT -->

    </div>
    <!-- row -->
  </div>
  <!-- container -->
</div>
<!-- /MAIN HEADER -->

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb-tree">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="active">All Categories</li>
        </ul>
      </div>
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">

      <!-- STORE -->
      <div id="store" class="col-sm">
        <!-- store categories -->
        @foreach($categories->chunk(4) as $categories_chunk)
          <div class="row">
            @foreach ($categories_chunk as $category)
              <!-- categories -->
              <div class="col-md-3 col-xs-3">
                <div class="product">
                  <div class="product-img">
                    @if ($category->filename)
                      <img src="{{ url('uploads/'.$category->filename) }}" alt="{{ $category->filename }}" />
                    @else
                      <img src="{{ asset('img/blank.png') }}" alt="blank" />
                    @endif
                  </div>

                  <div class="product-body">
                    <h3 class="product-name">
                      <a href="{{ url('/categories/'.$category->id) }}">
                        {{ $category->name }}
                      </a>
                    </h3>
                    <p class="product-category">
                      {{ $category->desc }}
                    </p>
                  </div>
                </div>
              </div>
              <!-- /categories -->
            @endforeach
          </div>
        @endforeach
        <!-- /categories -->
        <!-- Next and Previous links -->
        <div class="row text-center">
          {{ $categories->links() }}
        </div>

      </div>
      <!-- /STORE -->
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->
@endsection
