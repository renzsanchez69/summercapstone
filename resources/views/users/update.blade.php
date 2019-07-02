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
          <li><a href="{{ route('profile') }}">Profile</a></li>
          <li class="active">Edit</li>
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
        <h3 class="title">Edit Profile</h3>
        <a href="{{ route('updatePasswordForm') }}">Update Password</a>
      </div>
    </div>
    <!-- /row -->

    <!-- Validation Errors -->
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>
    <!-- /Validation Errors -->

    <!-- Update User -->
    <div class="row" id="updateUser">
      <div class="col-md-8">
        <form method="POST" action="{{ route('updateUser') }}">
          @csrf

          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="">
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="">
          </div>

          <div class="form-group">
            <label for="bday">Date of Birth</label>
            <input id="bday" type="date" class="form-control" name="bday">
          </div>

          <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" class="form-control" name="gender">
              <option selected></option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>

          <div class="form-group">
            <label for="address">Address</label>
            <input id="address" type="text" class="form-control" name="address">
          </div>

          <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input id="phone_number" class="form-control" type="number" name="phone_number">
          </div>

          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
    <!-- /Update User -->

  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->
@endsection

