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
          <li><a href="{{ route('adminSellersList') }}">All Sellers</a></li>
          <li class="active">{{ $seller->name }}</li>
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
        <h3 class="title">Seller</h3>
      </div>
    </div>

    <!-- row -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        <div class="product">
          <div class="product-body" style="text-align: left !important;">
            <h3 class="product-name">Seller ID: {{ $seller->id }}</h3>
            <h3 class="product-name">Name: {{ $seller->user->name }}</h3>
            <p class="product-category">Phone: {{ $seller->user->phone_number }}</p>
            <p class="product-category">Email: {{ $seller->user->email }}</p>
            <p class="product-category">Address: {{ $seller->user->address }}</p>
            <p class="product-category">Location: {{ $seller->location }}</p>
            <p class="product-category">Products Sold: {{ $seller->products_sold }}</p>
            <p class="product-category">Products Posted: {{ $seller->products_posted }}</p>
            <p class="product-category">Products Count: {{ $seller->products_count }}</p>
            <p class="product-category">Stars: {{ $seller->stars }}</p>
          @if ($seller->status == 0)
            <p class="product-category">Status: Applicant</p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSellerModal">
              Approve
            </button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#disapproveSellerModal">
              Disapprove
            </button>
          @elseif ($seller->status == 1)
            <p class="product-category">Status: Active</p>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#revokeSellerModal">
              Revoke
            </button>
          @elseif ($seller->status == -1)
            <p class="product-category">Status: Inactive</p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reactivateSellerModal">
              Reactivate
            </button>
          @elseif ($seller->status == -2)
            <p class="product-category">Status: Disapproved</p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSellerModal">
              Approve
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
<div class="modal fade bd-example-modal-sm" id="approveSellerModal" tabindex="-1" role="dialog" aria-labelledby="approveSellerLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="approveSellerLabel" style="font-weight: 500; font-size: 1.5rem;" >Approve Seller?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateSellersStatus', ['id' => $seller->id]) }}" >
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
<div class="modal fade bd-example-modal-sm" id="disapproveSellerModal" tabindex="-1" role="dialog" aria-labelledby="disapproveSellerLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="disapproveSellerLabel" style="font-weight: 500; font-size: 1.5rem;" >Disapprove Seller?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateSellersStatus', ['id' => $seller->id]) }}" >
          @csrf
          <input type="text" name="status" value="-2" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Revoke Seller Modal -->
<div class="modal fade bd-example-modal-sm" id="revokeSellerModal" tabindex="-1" role="dialog" aria-labelledby="revokeSellerLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="revokeSellerLabel" style="font-weight: 500; font-size: 1.5rem;" >Revoke Seller?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateSellersStatus', ['id' => $seller->id]) }}" >
          @csrf
          <input type="text" name="status" value="-1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Reactivate Seller Modal -->
<div class="modal fade bd-example-modal-sm" id="reactivateSellerModal" tabindex="-1" role="dialog" aria-labelledby="reactivateSellerLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="reactivateSellerLabel" style="font-weight: 500; font-size: 1.5rem;" >Reactivate Seller?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateSellersStatus', ['id' => $seller->id]) }}" >
          @csrf
          <input type="text" name="status" value="1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
