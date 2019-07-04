<?php

use App\Http\Controllers\UtilitiesController;
?>
@extends('layouts.base')

@section('content')
@include('layouts.header')
@include('layouts.navigation')


<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb-tree">
          <li class="active">Seller History</li>
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
    <div class="row">

      <!-- STORE -->
      <div id="store" class="col-sm">

        <!-- products table -->
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Order Date</th>
              <th scope="col">Product Name</th>
              <th scope="col">Category</th>
              <th scope="col">QTY</th>
              <th scope="col">Customer</th>
              <th scope="col">Customer Contact</th>
              <th scope="cole">Payment Method</th>
              <th scope="cole">Amount</th>
            </tr>
          </thead>
          <tbody>

            <?php

            $cashOnDelivery = 0;
            $visa = 0;

            ?>
            @foreach ($history as $item)

            <?php

            $itemTotalAmount = $item->qty * $item->total;

            $paymentMethod = $item->payment_method;
            $paymentDisplay = $paymentMethod === 0 ? "Cash on delivery" : "Visa Card";

            if ($paymentMethod === 0) {
              $cashOnDelivery += $itemTotalAmount;
            } else {
              $visa += $itemTotalAmount;
            }

            ?>

            <tr>
              <td scope="row">{{ date("F j, Y, g:i A", strtotime($item->created_at)) }}</td>
              <td>{{ $item->product_name }}</td>
              <td>{{ $item->category }}</td>
              <td>{{ $item->qty }}</td>
              <td>{{ $item->customer }}</td>
              <td>{{ $item->phone_number }}</td>

              <td>{{ $paymentDisplay }}</td>

              <td>K {{ UtilitiesController::monetize(true, $itemTotalAmount) }}</td>
            </tr>
            @endforeach

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Cash on Delivery Total: <i>K {{ UtilitiesController::monetize(true, $cashOnDelivery) }}</i></strong></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Visa Total: <i>K {{ UtilitiesController::monetize(true, $visa) }}</i></strong></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Total: <i>K {{ UtilitiesController::monetize(true, $visa + $cashOnDelivery) }}</i></strong></td>
            </tr>

          </tbody>
        </table>

        <br>
        <br>
        <br>
        <!-- Next and Previous links -->
        <div class="row text-center">
          {{ $history->links() }}
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