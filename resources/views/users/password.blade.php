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
                    <li class="active">Update Password</li>
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


    <!-- Update Password -->
    <div class="row" id="updatePassword">
        <div class="col-md-8 col-md-offset-2 order-details">
            <div class="section-title text-center">
                <h3 class="title">Update Password</h3>
            </div>
            <form method="POST" action="{{ route('updatePassword') }}">
                @csrf


                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input id="current_password" type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" required>
                    @if ($errors->has('current_password'))
                        <span class="invalid-feedback text-danger" role="alert">
                            <strong>{{ $errors->first('current_password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback text-danger" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm New Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="row">
                    <div class="col-xs-3 col-xs-offset-9">
                        <button type="submit" class="btn btn-primary btn-small btn-block">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Update Password -->

  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->
@endsection

