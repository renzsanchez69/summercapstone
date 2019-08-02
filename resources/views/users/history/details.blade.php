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
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('userHistory') }}">All Orders</a></li>
          <li class="active">Order History</li>
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
        <h3 class="title">Order</h3>
      </div>

      @if( Session::has('confirmSuccess'))
      <div class="alert alert-success">
        <strong>Success!</strong> {{ Session::get('confirmSuccess') }}
      </div>
      @endif


    </div>


    <!-- row -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        <div class="product">
          <div class="product-body" style="text-align: left !important;">
            <h3 class="product-name">Order Date: {{ date("F j, Y ", strtotime($order->order_date)) }}</h3>
            <h3 class="product-name">Order Address: {{ $order->address }}</h3>
            @if($order->delivery_status == 0)
            <h3 class="product-name">Order Status: In Transit</h3>
            @elseif($order->delivery_status == 1)
            <h3 class="product-name">Order Status: Delivered</h3>
            @elseif($order->delivery_status == 2)
            <h3 class="product-name">Order Status: Cancelled</h3>
            @endif

            <h3 class="product-name">Products:</h3>
            <ul>
              @foreach ($products as $indexKey => $product)
              <li>{{ $product->pivot->qty }} x {{ $product->name }} - K {{$product_total[$indexKey]}} (Seller: {{$product_username[$indexKey]}})</li>
              @endforeach
            </ul>
            <br />
            <h3 class="product-name">Total: K {{ $order->total }}</h3>
            <h3 class="product-name">Payment Status: {{ $order->payment_status }}</h3>
            <h3 class="product-name">Payment Date: {{ $order->payment_date }}</h3>
            @if($order->payment_method == 1)
            <h3 class="product-name">Billing Address: {{ $order->billing_address }}</h3>
            @endif

            @if($order->delivery_status !== 3 && $order->delivery_status !== 2)
            <form method="POST" action="{{ route('confirmOrder', $order->id) }}">
              @csrf
              <input type="hidden">
              <button type="submit" class="btn btn-success">Receive Order</button>
            </form>
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