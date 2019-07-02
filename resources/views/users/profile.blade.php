@extends('layouts.base')

@section('content')
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-sm">
                <h3 class="title">User Profile</h3>
                <br>
            </div>
        </div>
        <!-- /row -->

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
                <div class="aside row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <a href="{{ route('updatePasswordForm') }}"
                            class="btn btn-success btn-sm btn-block"
                            role="button"
                            aria-pressed="true">
                                Update Password
                        </a>
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
            @if (!$seller)
                <div class="aside row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <button type="button"
                            class="btn btn-info btn-sm btn-block"
                            data-toggle="modal"
                            data-target="#sellerModal">
                                Apply as Seller
                        </button>
                    </div>
                </div>
            @elseif ($seller->status == 1)
                <div class="aside row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <a href="{{ route('sellersProfile', ['id' => $seller->id]) }}"
                            class="btn btn-primary btn-sm btn-block"
                            role="button"
                            aria-pressed="true">
                                Seller Profile
                        </a>
                    </div>
                </div>
                <!-- /aside Widget -->
            @endif

 				<!-- aside Widget -->
                <div class="aside row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <a href="{{ route('deactivateForm') }}"
                            class="btn btn-danger btn-sm btn-block"
                            role="button"
                            aria-pressed="true">
                                Deactivate Account
                        </a>
                    </div>
                </div>
                <!-- /aside Widget -->


			</div>
            <!-- /ASIDE -->

            <!-- ASIDE -->
			<div class="col-md-8">
                @if (Session::has('success'))
                    <div class="alert alert-success text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                <form method="POST" action="{{route('users.update', $user)}}">
                    @csrf
                    {{ method_field('patch') }}

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" value="{{ $user->name}}">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" name="username" value="{{ $user->username}}">
                        @if ($errors->has('username'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="name">Age</label>
                        <input type="text" class="form-control" id="age" name="age" value="{{ $age}}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input id="date_of_birth" type="date" class="form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" name="date_of_birth" value="{{ $user->date_of_birth }}">
                        @if ($errors->has('date_of_birth'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('date_of_birth') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ $user->email }}">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender">
                            <option @if ($user->gender == "Male") selected @endif>Male</option>
                            <option @if ($user->gender == "Female") selected @endif>Female</option>
                        </select>
                        @if ($errors->has('gender'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $user->address }}">
                        @if ($errors->has('address'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input id="phone_number" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" type="number" name="phone_number" value="{{ $user->phone_number }}">
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-xs-2 col-xs-offset-10">
                            <button type="submit" class="btn btn-primary btn-small btn-block">Update</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /ASIDE -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->
@endsection

@section('modals')
<!-- Apply Seller Modal -->
<div class="modal fade bd-example-modal-sm" id="sellerModal" tabindex="-1" role="dialog" aria-labelledby="sellerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="sellerModalLabel" style="font-weight: 500; font-size: 1.5rem;" >The admin will review your profile.</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="{{ url('/sellers/apply/'.$user->id) }}" role="button">Proceed</a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Profile Modal -->
<div class="modal fade bd-example-modal-sm" id="deleteProfileModal" tabindex="-1" role="dialog" aria-labelledby="deleteProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <!-- Note: Flexbox used to align contents in modal header -->
      <div class="modal-header" style="padding: 1rem; display: flex; align-items: flex-start; justify-content: space-between; ">
        <h4 class="modal-title" id="deleteProfileLabel" style="font-weight: 500; font-size: 1.5rem;" >Deactivate Account?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem auto; padding: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a class="btn btn-danger" href="" role="button">Confirm</a>
      </div>
    </div>
  </div>
</div>
@endsection
