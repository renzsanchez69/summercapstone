@extends('layouts.base')

@section('content')

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb-tree">
          <li><a href="{{ route('admin') }}">Admin</a></li>
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

    <div class="row">
      <div class="col-sm">
        <h3 class="title">Categories</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <a class="btn btn-primary" href="{{ route('adminCategoriesCreateForm') }}" role="button">Create Category</a>
      </div>
    </div>

    <!-- row -->
    <div class="row">

      <!-- STORE -->
      <div id="store" class="col-sm">

        <!-- store categories -->
        <div class="row">
          @foreach ($categories as $category)
          <!-- product -->
          <div class="col-md-4 col-xs-6">
            <div class="product">
              <div class="product-img">
              @if ($category->filename)
                <img src="{{ url('uploads/'.$category->filename) }}" alt="{{ $category->filename }}" />
              @else
                <img src="{{ asset('img/blank.png') }}" alt="blank" />
              @endif
              </div>
              <div class="product-body">
                <h3 class="product-name"><a href="{{ url('/admin/categories/'.$category->id) }}">{{ $category->name }}</a></h3>
                <p class="product-category">{{ $category->desc }}</p>
              </div>
            </div>
          </div>
          <!-- /categories -->
          @endforeach
        </div>

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
