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
              <th scope="col">QTY</th>
              <th scope="col">Customer</th>
              <th scope="col">Customer Contact</th>
              <th scope="cole">Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($history as $item)
            <tr>
              <th scope="row">{{ $item->created_at }}</th>
              <td>{{ $item->product_name }}</td>
              <td>{{ $item->qty }}</td>
              <td>{{ $item->customer }}</td>
              <td>{{ $item->phone_number }}</td> 
            </tr>
            @endforeach
            @foreach($products as $products)
             {{$products->name}} <br>price: {{$products->price}}
            @endforeach
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