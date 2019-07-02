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
          <li class="active">Manage Orders</li>
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

        <!-- products table -->
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Order ID</th>
              <th scope="col">Order date</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($orders as $item)
            <tr>
              <th scope="row">{{ $item->id }}</th>
              <td>{{ $item->order_date }}</td>
              <td>K {{ $item->total }}</td>
                @if ($item->delivery_status == 0)
                    <td>In Transit </td>
                @elseif ($item->delivery_status == 1)
                    <td>Delivered</td>
                @elseif ($item->delivery_status == 2)
                    <td>Cancelled</td>
                @endif
              <td><a href="{{ route('adminOrdersDetails', $item->id)  }}">Manage Order</a></td>
            </tr>
          @endforeach
          </tbody>
        </table>

        <br>
        <br>
        <br>
        <!-- Next and Previous links -->
        <div class="row text-center">
          {{ $orders->links() }}
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
