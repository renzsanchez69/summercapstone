@extends('layouts.base')

@section('content')

<!-- SECTION -->
<div class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">

      <!-- section title -->
      <div class="col-md-12">
        <div class="section-title">
          <h3 class="title"><a href="{{ route('admin') }}">Admin</a></h3>
        </div>
      </div>
      <!-- /section title -->

      <div id="store" class="col-md-12">
        <div class="row">
          <div class="col-md-4 col-xs-6">
            <div class="">
              <h4 class=""><a href="{{ route('adminCategoriesList') }}">Categories</a></h4>
              <h4 class=""><a href="{{ route('adminUsersList') }}">Users</a></h4>
              <!-- <h4 class=""><a href="{{ route('adminSellersList') }}">Sellers</a></h4> -->
              <h4 class=""><a href="{{ route('adminProductsList') }}">Product Review</a></h4>
              <h4 class=""><a href="{{ route('adminOrdersList') }}">Manage Orders</a></h4>
            </div>
          </div>
        </div>
        </div>

      </div>

    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->

@endsection
