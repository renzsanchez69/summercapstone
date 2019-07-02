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
          <li><a href="{{ route('adminProductsList') }}">All Products</a></li>
          <li class="active">{{ $product->name }}</li>
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
        <h3 class="title">Products</h3>
      </div>
    </div>

    <!-- row -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        <div class="product">
          <div class="product-body" style="text-align: left !important;">
            <h3 class="product-name">Product ID: {{ $product->id }}</h3>
            <h3 class="product-name">Name: {{ $product->name }}</h3>
            <div class="product-img">
              @if (!$product->images->isEmpty())
              <img src="{{ url('uploads/'.$product->images[0]->filename) }}" alt="{{ $product->images[0]->filename }}" />
              @else
              <img src="{{ asset('img/blank.png') }}" alt="blank" />
              @endif
            </div>
            <p class="product-category">Price: {{ $product->price }}</p>
            <p class="product-category">Description: {{ $product->desc }}</p>
            <p class="product-category">Location: {{ $product->location }}</p>
            <p class="product-category">Category: {{ $product->category->name }}</p>
            <p class="product-category">Seller: {{ $product->seller->user["name"] }}</p>
            @if ($product->status == 1)
            <p class="product-category">Status: Approved</p>
            @elseif ($product->status == 0)
            <p class="product-category">Status: For Review</p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveProductModal">
              Approve
            </button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#disapproveProductModal">
              Disapprove
            </button>
            @elseif ($product->status == -1)
            <p class="product-category">Status: Disapproved</p>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-4"></div>
    </div>

    <!-- /row -->

  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->

@endsection

@section('modals')
<!-- Approve Product Modal -->
<div class="modal fade bd-example-modal-sm" id="approveProductModal" tabindex="-1" role="dialog" aria-labelledby="approveProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="approveProductLabel" style="font-weight: 500; font-size: 1.5rem;">Approve Product?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateProductsStatus', ['id' => $product->id]) }}">
          @csrf
          <input type="text" name="status" value="1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Disapprove Product Modal -->
<div class="modal fade bd-example-modal-sm" id="disapproveProductModal" tabindex="-1" role="dialog" aria-labelledby="disapproveProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="disapproveProductLabel" style="font-weight: 500; font-size: 1.5rem;">Disapprove Product?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateProductsStatus', ['id' => $product->id]) }}">
          @csrf
          <input type="text" name="status" value="-1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection