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
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="active">User Order History</li>
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
        <h3 class="title">Orders</h3>
      </div>
    </div>

    <!-- row -->
    <div class="row">

      <!-- STORE -->
      <div id="store" class="col-sm">


        <?php
        $total = 0;
        ?>

        <!-- products table -->
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Order ID</th>
              <th scope="col">Product Name</th>
              <th scope="col">Order date</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
              <th scope="col">Payment Method</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($history as $item)

            <?php
            $amount = $item->order_total_amount;
            $total += $amount;
            ?>
            <tr>
              <td scope="row">{{ $item->id }}</td>

              <td scope="row">{{ $item->name }}</td>

              <td>{{ date("F j, Y, g:i A", strtotime($item->order_date)) }}</td>

              @if ($item->delivery_status == 0)
              <td>In Transit </td>
              @elseif ($item->delivery_status == 1)
              <td>Delivered</td>
              @elseif ($item->delivery_status == 2)
              <td>Cancelled</td>
              @endif

              <td><a href="{{ route('userOrder', $item->id)  }}">Manage Order</a></td>

              <?php
              $paymentMethod = $item->payment_method;
              $paymentDisplay = $paymentMethod === 0 ? "Cash on delivery" : "Visa Card";
              ?>

              <td scope="row">{{ $paymentDisplay }}</td>

              <td>K <?php echo UtilitiesController::monetize(true, $amount); ?></td>
            </tr>

            @endforeach

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>
                <strong>
                  <i>Total: K {{ UtilitiesController::monetize(true, $total) }}</i>
                </strong>
              </td>
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