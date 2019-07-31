<?php

use App\Http\Controllers\UtilitiesController;

function addLeadingZero($number)
{
  return str_pad($number, 2, "0", STR_PAD_LEFT);
}


function getCurrentWeekNumber()
{
  return date("l") === "Sunday" ? date("W") + 1 : date("W");
}

function getWeekDates($year, $weekNumber)
{
  $sanitized = addLeadingZero($weekNumber);
  $currentWeekDates = array();
  for ($i = 0; $i < 7; $i++) {
    $currentWeekDates[] = date("Y-m-d", strtotime("$year-W$sanitized-0 + $i days"));
  }
  return $currentWeekDates;
}

function getCurrentWeekDates($givenTime = false)
{
  return getWeekDates(date("Y"), getCurrentWeekNumber());
}
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
      <button onclick="displayRow('all')" id="showCod">All</button>
      <button onclick="displayRow('cod')" id="showCod">Cash on Delivery</button>
      <button onclick="displayRow('visa')" id="showVisa">VISA Payment</button>
      <button onclick="displayRow('today')" id="showCod">Today's Sales</button>
      <button onclick="displayRow('week')" id="showCod">This Week's Sales (Week {{ date("W") }})</button>
      <button onclick="displayRow('month')" id="showCod">This Month's Sales ({{ date("F") }})</button>

      <span>
        <select class="filter-selector" id="weekSelector" data-selector-type="week">
          <option value="" disabled selected hidden>Select week</option>
          <?php
          for ($i = 0; $i < 52; $i++) {
            ?>
            <option value="{{ $i + 1 }}">Week {{ $i + 1 }}</option>
          <?php } ?>
        </select>
      </span>

      <span>
        <select class="filter-selector" id="monthSelector" data-selector-type="month">
          <option value="" disabled selected hidden>Select month</option>
          <?php
          $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
          for ($i = 0; $i < count($months); $i++) {
            ?>
            <option value="{{ $i + 1 }}">{{ $months[$i] }}</option>
          <?php } ?>
        </select>
      </span>

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
              <th scope="col">Price</th>
              <th scope="col">Order Quantity</th>
              <th scope="col">Remaining Stocks</th>
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

            $currentDate = date("Y-m-d");

            $weekDates = getCurrentWeekDates($givenTime = false);

            $currentMonth = date('m');

            $todaySales = 0;

            ?>

            <?php
            foreach ($history as $item) {
              $itemTotalAmount = 0;
              $itemTotalAmount = $item->qty * $item->presyo;

              $paymentMethod = $item->payment_method;
              $paymentDisplay = $paymentMethod === 0 ? "Cash on delivery" : "Visa Card";

              if ($paymentMethod === 0) {
                $cashOnDelivery += $itemTotalAmount;
              } else {
                $visa += $itemTotalAmount;
              }
              $orderDate = date("Y-m-d", strtotime($item->created_at));
              $isInCurrentWeek = in_array($orderDate, $weekDates) ? true : false;


              // Today
              $isToday = null;
              if ($currentDate === date("Y-m-d", strtotime($item->created_at))) {
                $todaySales += $itemTotalAmount;
                $isToday = true;
              }

              ?>

              <tr data-amount="<?php echo $itemTotalAmount; ?>" class="methods method-all method-<?php echo $paymentMethod;  ?><?php echo " month-" . date("n", strtotime($item->created_at)) ?><?php echo " week-" . date("W", strtotime($item->created_at)) ?><?php echo $isToday ? " ordered-today" : false ?><?php echo date("m", strtotime($item->created_at)) === $currentMonth ? " current-month" : false ?><?php echo $isInCurrentWeek ? " current-week" : false ?>">

                <td scope="row">{{ date("F j, Y ", strtotime($item->created_at)) }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->category }}</td>
                <td>K {{ UtilitiesController::monetize(true, $item->presyo) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->remaining_stock }}</td>
                <td>{{ $item->customer }}</td>
                <td>{{ $item->phone_number }}</td>
                <td>{{ $paymentDisplay }}</td>

                <td>K {{ UtilitiesController::monetize(true, $itemTotalAmount) }}</td>
              </tr>
            <?php } ?>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Cash on Delivery Total: <i>K <span id="codTotal">{{ UtilitiesController::monetize(true, $cashOnDelivery) }}</span></i></strong></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Visa Total: <i>K <span id="visaTotal">{{ UtilitiesController::monetize(true, $visa) }}</span></i></strong></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Total: <i>K <span id="overallTotal">{{ UtilitiesController::monetize(true, $visa + $cashOnDelivery) }}</span></i></strong></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><strong>Today's Sales: <i>K <span id="overallTotal">{{ UtilitiesController::monetize(true, $todaySales) }}</span></i></strong></td>
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

<script>
  function displayRow(type) {
    $(".methods").removeClass("visibleOrders").hide();
    var method;
    if (type === "cod") {
      method = ".method-0";
    } else if (type === "visa") {
      method = ".method-1";
    } else if (type === "all") {
      method = ".methods";
    } else if (type === "month") {
      method = ".current-month";
    } else if (type === "week") {
      method = ".current-week";
    } else if (type === "today") {
      method = ".ordered-today";
    } else {
      method = "1";
    }
    $(method).addClass("visibleOrders").show();
    total();
  }

  function total() {
    var codTotal = 0;
    var visaTotal = 0;
    $(".visibleOrders").each(function(i, val) {
      var amount = parseInt($(this).attr("data-amount"));
      if ($(this).hasClass("method-0")) {
        codTotal += parseInt($(this).attr("data-amount"));
      } else {
        visaTotal += parseInt($(this).attr("data-amount"));
      }
    });
    $("#codTotal, #visaTotal, #overallTotal").html("");
    $("#codTotal").html(codTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    $("#visaTotal").html(visaTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    $("#overallTotal").html((codTotal + visaTotal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
  }
</script>
<script type="text/javascript">
  $(function() {
    $(document).on("change", ".filter-selector", function(e) {
      var selectorType = $(this).attr("data-selector-type");
      var weekNumber = $(this).children("option:selected").attr("value");
      $(".methods").removeClass("visibleOrders").hide();
      $(`.${selectorType}-${weekNumber}`).addClass("visibleOrders").show();
      total();
    });
  });
</script>


@endsection