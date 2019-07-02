@extends('layouts.base')

@section('content')
<div class="section">
  <div class="container">

    @if( Session::has('message'))
    <div class="alert alert-success">
      <strong>Success!</strong> {{ Session::get('message') }}
    </div>
    @endif

    @if( Session::has('deac'))
    <div class="alert alert-success">
      <strong>Success!</strong> {{ Session::get('deac') }}
    </div>
    @endif

    <div class="col-sm-12">
      <div class="section-title">
        <h3 class="title">{{ __('Login') }}</h3>
      </div>
    </div>

    <div class="col-sm-12">
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group row">
          <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
          <div class="col-md-6">
            <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>
            @if ($errors->has('username'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('username') }}</strong>
            </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
          <div class="col-md-6">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>
        </div>

        <div class="form-group row text-right">
          <div class="col-md-10">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>

        <div class="form-group row text-right">
          <div class="col-md-10">
            <button type="submit" class="btn btn-primary">
              {{ __('Login') }}
            </button>
            @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}" style="padding-right: 0px;">
              {{ __('Forgot Your Password?') }}
            </a>
            @endif
          </div>
          <div class="col-md-2"></div>
        </div>

      </form>
    </div>
  </div>
</div>
</div>
@endsection


@section('scripts')

@endsection