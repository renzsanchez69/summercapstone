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
          <li><a href="{{ route('adminUsersList') }}">All users</a></li>
          <li class="active">{{ $user->name }}</li>
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
        <h3 class="title">User Details</h3>
      </div>
    </div>

    <!-- row -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        <div class="product">
          <div class="product-body" style="text-align: left !important;">
            <h3 class="user-name">User ID: {{ $user->id }}</h3>
                <h3 class="user-name">Name: {{ $user->name }}</h3>
                <p class="user-category">Username: {{ $user->username }}</p>
                <p class="user-category">Email: {{ $user->email }}</p>
                <p class="user-category">Address: {{ $user->address }}</p>
                <p class="user-category">Phone Number: {{ $user->phone_number }}</p>
                @if (empty($user->deleted_at))
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reactivateUserModal">
                        Deactivate
                    </button>
                @else 
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deactivateUserModal">
                        Reactivate
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
<!-- Approve user Modal -->
<div class="modal fade bd-example-modal-sm" id="reactivateUserModal" tabindex="-1" role="dialog" aria-labelledby="reactivateUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="reactivateUserLabel" style="font-weight: 500; font-size: 1.5rem;" >Activate User?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateUsersStatus', ['id' => $user->id]) }}" >
          @csrf
          <input type="text" name="status" value="1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Disapprove user Modal -->
<div class="modal fade bd-example-modal-sm" id="deactivateUserModal" tabindex="-1" role="dialog" aria-labelledby="deactivateUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="deactivateUserLabel" style="font-weight: 500; font-size: 1.5rem;" >Deactivate user?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('adminUpdateUsersStatus', ['id' => $user->id]) }}" >
          @csrf
          <input type="text" name="status" value="-1" hidden />
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>