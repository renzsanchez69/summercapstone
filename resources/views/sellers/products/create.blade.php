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
          <li><a href="{{ route('sellersProfile', ['id' => $id]) }}">Seller Profile</a></li>
          <li><a href="{{ route('sellersProductsList', ['id' => $id]) }}">All Products</a></li>
          <li class="active">Create</li>
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
        <h3 class="title">Create Product</h3>
      </div>
    </div>

    <!-- Validation Errors -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
      </div>
    </div>
    <!-- /Validation Errors -->

    <!-- Create Product -->
    <div class="row" id="createProduct">
      <div class="col-md-8">
        <form method="POST" action="{{ route('storeSellersProducts') }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="productCategory">Category</label>
            <select class="form-control" id="productCategory" name="productCategory">
              @foreach ($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="productName">Name</label>
            <input type="text" class="form-control" id="productName" name="productName" placeholder="" />
          </div>
          <div class="form-group">
            <label for="productDescription">Quantity</label>
            <select id="qty" name="qty">
              <?php for ($i = 1; $i <= 999; $i++) : ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
              <?php endfor; ?>
            </select>

          </div>
          <div class="form-group">
            <label for="productImages">Image</label>
            <input type="file" class="form-control" id="productImages" name="productImages[]" multiple />
          </div>
          <div class="form-group">
            <label for="productPrice">Price</label>
            <input type="text" class="form-control" id="productPrice" name="productPrice" placeholder="" />
          </div>
          <div class="form-group">
            <label for="productDescription">Description</label>
            <input type="text" class="form-control" id="productDescription" name="productDescription" placeholder="" />
          </div>
          <div class="form-group">
            <small id="" class="form-text text-muted">By submitting, you agree to the Terms and Conditions of Niugini-hub.</small>
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
    <!-- /Create Product -->
  </div>
</div>
@endsection