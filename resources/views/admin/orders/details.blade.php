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
          <li><a href="{{ route('adminOrdersList') }}">Manage Orders</a></li>
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
    </div>

    <!-- row -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        <div class="product">
          <div class="product-body" style="text-align: left !important;">
            <h3 class="product-name">Order Date: {{ $order->order_date }}</h3>
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
              <li>{{ $product->pivot->qty }} x {{ $product->name }} - K {{$product_total[$indexKey]}} (sold by: {{$product_username[$indexKey]}})</li>
              @endforeach
            </ul>
            <br />
            <h3 class="product-name">Total: K {{ $order->total }}</h3>
            <h3 class="product-name">Payment Status: {{ $order->payment_status }}</h3>
            <h3 class="product-name">Payment Date: {{ $order->payment_date }}</h3>
            @if($order->payment_method == 1)
            <h3 class="product-name">Billing Address: {{ $order->billing_address }}</h3>
            @endif
            @if($order->delivery_status == 0)
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deliverOrderModal">
              Confirm Delivery
            </button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelOrderModal">
              Cancel Delivery
            </button>
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
<!-- Approve Seller Modal -->
<div class="modal fade bd-example-modal-sm" id="deliverOrderModal" tabindex="-1" role="dialog" aria-labelledby="deliverOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="deliverOrderLabel" style="font-weight: 500; font-size: 1.5rem;">Confirm Delivery?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateOrdersStatus', ['id' => $order->id]) }}">
          @csrf
          <input type="text" name="status" value="1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Disapprove Seller Modal -->
<div class="modal fade bd-example-modal-sm" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="cancelOrderLabel" style="font-weight: 500; font-size: 1.5rem;">Cancel Delivery?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateOrdersStatus', ['id' => $order->id]) }}">
          @csrf
          <input type="text" name="status" value="2" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection