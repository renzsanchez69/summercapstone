@extends('layouts.base')

@section('content')
<div class="section">
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-sm-12">
        <div class="section-title">
          <h3 class="title">{{ __('Registration Form') }}</h3>
        </div>
      </div>

      <div class="col-sm-12">
        <form method="POST" action="{{ route('register') }}">
          @csrf

          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Name') }}</label>
            <div class="col-md-6">
              <input id="name" type="text" placeholder="Enter Full Name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
              @if ($errors->has('name'))
              <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
            <div class="col-md-6">
              <input id="username" type="text" placeholder="Enter Username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" required autofocus>
              @if ($errors->has('username'))
              <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $errors->first('username') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
            <div class="col-md-6">
              <input id="password" type="password" placeholder="atleast 8 character" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
              @if ($errors->has('password'))
              <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
            <div class="col-md-6">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
            <div class="col-md-6">
              <input id="email" type="email" placeholder="Enter Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
              @if ($errors->has('email'))
              <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="bday" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>
            <div class="col-md-6">
              <input id="bday" type="date" min="1900-01-01" max="<?php echo date('Y') - 18 ?>-12-31" class="form-control" name="bday">
            </div>
          </div>

          <div class="form-group row">
            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>
            <div class="col-md-6">
              <select id="gender" class="form-control" name="gender">
                <option selected></option>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>
            <div class="col-md-6">

              <input id="phone_number" maxlength="8" type="number" value="7" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number') }}" required autofocus>

              @if ($errors->has('phone_number'))
              <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $errors->first('phone_number') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
            <div class="col-md-6">
              <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}">
            </div>
          </div>

          <div class="form-group row text-right">
            <div class="col-md-10">
              <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
              </button>
            </div>
            <div class="col-md-2"></div>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
@endsection