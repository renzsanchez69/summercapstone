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
                    <li class="active">Seller Profile</li>
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
            <div class="col-sm">
                <h3 class="title">Seller Profile</h3>
                <br>
            </div>
        </div>
        <!-- /row -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <?php
        if (isset($_GET["success"])) {

            ?>

            <script>
                window.onload = function() {
                    $("#successModal").modal('show');
                }
            </script>

        <?php } ?>

        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        The product is being reviewed by the admin.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>

        <!-- row -->
        <div class="row">
            <!-- ASIDE -->
            <div class="col-md-4">
                <!-- aside Widget -->
                <div class="aside">
                    <div class="card text-center">
                        <div class="card-header">
                            {{-- <img src="{{ asset('img/blank.png') }}" class="rounded-circle img-fluid img-thumbnail" alt="pic" height="250" width="250" /> --}}
                        </div>
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                @if ($seller->status == 1)
                <div class="aside row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <a href="{{ route('productsCreateForm') }}" class="btn btn-success btn-sm btn-block" role="button" aria-pressed="true">
                            Create Product
                        </a>
                    </div>
                </div>
                @endif
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <a href="{{ route('sellersProductsList', ['id' => $seller->id]) }}" class="btn btn-info btn-sm btn-block" role="button" aria-pressed="true">
                            View Products
                        </a>
                    </div>
                </div>
                <!-- /aside Widget -->
            </div>
            <!-- /ASIDE -->

            <!-- ASIDE -->
            <div class="col-md-8">
                <h5 class="product-name">Seller ID: {{ $seller->id }}</h5>
                <h5 class="product-name">Name: {{ $seller->user->name }}</h5>
                <p class="product-category">Phone: {{ $seller->user->phone_number }}</p>
                <p class="product-category">Email: {{ $seller->user->email }}</p>
                <p class="product-category">Address: {{ $seller->user->address }}</p>
                <p class="product-category">Location: {{ $seller->location }}</p>
                <p class="product-category">Products Sold: {{ $seller->products_sold }}</p>
                <p class="product-category">Products Posted: {{ $seller->products_posted }}</p>
                <p class="product-category">Products Count: {{ $seller->products_count }}</p>
                <p class="product-category">Stars: {{ $seller->stars }}</p>
                @if ($seller->status == 1)
                <p class="product-category">Status: Active</p>
                @elseif ($seller->status == 0)
                <p class="product-category">Status: For Approval</p>
                @else
                <p class="product-category">Status: Inactive</p>
                @endif
            </div>
            <!-- /ASIDE -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->


@endsection