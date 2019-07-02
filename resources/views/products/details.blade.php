@extends('layouts.base')

@section('content')
@include('layouts.header')

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb-tree">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ url('/categories/'.$product->category['id']) }}">{{ $product->category['name'] }}</a></li>
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
    <!-- row -->
    <div class="row">
      <!-- Product main img -->
      <div class="col-md-5 col-md-push-2">
        <div id="product-main-img">
          @forelse ($product->images as $image)
          <div class="product-preview">
            <img src="{{ url('uploads/'.$image->filename) }}" alt="{{ $image->filename }}" />
          </div>
          @empty
          <div class="product-preview">
            <img src="{{ asset('img/blank.png') }}" alt="blank" />
          </div>
          @endforelse
        </div>
      </div>
      <!-- /Product main img -->

      <!-- Product thumb imgs -->
      <div class="col-md-2 col-md-pull-5">
        <div id="product-imgs">
          @forelse ($product->images as $image)
          <div class="product-preview">
            <img src="{{ url('uploads/'.$image->filename) }}" alt="{{ $image->filename }}" />
          </div>
          @empty
          <div class="product-preview">
            <img src="{{ asset('img/blank.png') }}" alt="blank" />
          </div>
          @endforelse
        </div>
      </div>
      <!-- /Product thumb imgs -->

      <!-- Modal -->
      <div id="tagay" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Confirm Delete</h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this product?</p>
            </div>
            <div class="modal-footer">

              <form style="display: inline-block;" method="POST" action="<?php echo "/products/delete/" . $product->id; ?>">
                @csrf
                <button type="submit" href="edit/{{ $product->id }}" class="btn btn-danger">Yes</button>
              </form>

              <button type="button" class="btn btn-default" data-dismiss="modal">No</>
            </div>
          </div>

        </div>
      </div>

      <!-- Product details -->
      <div class="col-md-5">
        <div class="product-details">

          <h2 class="product-name">{{ $product->name }}</h2>

          <?php
          if (Auth::check()) {
            if (Auth::user()->isSeller()) {

              $sellerId = $product->seller->id;
              $userId = Auth::user()->sellerId()[0]->id;

              $isOwnProduct = $sellerId === $userId ? true : false;

              if ($isOwnProduct) {
                ?>

                <a type="button" href="edit/{{ $product->id }}" class="btn btn-info">Edit</a>
                <span type="button" class="btn btn-danger" data-toggle="modal" data-target="#tagay">Delete</span>

              <?php
              }
            }
          }
          ?>

          <div>
            <h3 class="product-price">Seller: {{ $product->seller->user->username }}</h3>
          </div>

          <div>
            <?php
            require_once '..\app\Utilities\Tools.php';
            $price = Tools::monetize(true, $product->price);
            ?>
            <h3 class="product-price">K <?php echo $price; ?></h3>
          </div>

          <p>Description: {{ $product->desc }}</p>

          <h1>
            <?php
            $quantity = $product->qty;
            echo $quantity || $quantity > 0 ? "Quantity: $quantity" : "Out of stock";
            ?>
          </h1>

          <ul class="product-links">
            <li>Category:</li>
            <li><a href="{{ route('home') }}">{{ $product->category['name'] }}</a></li>
          </ul>

          <ul class="product-links">
            <li>Location:</li>
            <li>{{ $product->location  }}</li>
          </ul>


          <?php

          if (!(Auth::check() && Auth::user()->isSeller()) || !$isOwnProduct) { ?>
            <!-- Cart -->
            <div class="add-to-cart">
              <div class="qty-label">
                Quantity:
                <div class="input-number">
                  <input onkeydown="return false" type="number" id="product-qty" min="1" value="1">
                  <span class="qty-up">+</span>
                  <span class="qty-down">-</span>
                </div>
              </div>
              <button class="add-to-cart-btn" onclick="add_cart_detail({{ $product->id }})">
                <i class="fa fa-shopping-cart"></i>
                add to cart
              </button>
            </div>
            <!-- Cart -->
          <?php } ?>


        </div>
      </div>
      <!-- /Product details -->

    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->
@endsection
@section('modals')
<div class="modal fade bd-example-modal-sm" id="addCartModal" tabindex="-1" role="dialog" aria-labelledby="addCartLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="addCartLabel" style="font-weight: 500; font-size: 1.5rem;">Add to cart?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="product-id" id="product-id" value="">
        <input type="number" id="product-qty" min="1" value="1">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="add_cart_home()">Confirm</button>
      </div>
    </div>
  </div>
  @endsection