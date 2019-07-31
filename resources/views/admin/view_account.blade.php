@extends('layouts.admin_app')

@section('content')

@if (\Session::has('has_samples'))
    <div class="alert alert-danger offset-md-1 col-md-10">
        <a class="close" data-dismiss="alert">×</a>
        <strong>Error:</strong> {!!Session::get('has_samples')!!}
    </div>
@endif

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    My Account
                </div>
               
                <div class="card-body">
                    <form method="POST" action="{{ route('updateAccount-admin', ['accountId' => Auth::user()->employeeId]) }}">
                        @method('PATCH')
                        @csrf
    
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $account->username }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="old_password" class="col-md-4 col-form-label text-md-right">{{ __('Old Password') }}</label>

                            <div class="col-md-6">
                                <input id="old_password" type="password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password" required>

                                @if ($errors->has('old_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
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
                            <label for="employeeName" class="col-md-4 col-form-label text-md-right">{{ __('Employee Name') }}</label>

                            <div class="col-md-6">
                                <input id="employeeName" type="text" class="form-control{{ $errors->has('employeeName') ? ' is-invalid' : '' }}" name="employeeName" value="{{ $account->employeeName }}" required autofocus>

                                @if ($errors->has('employeeName'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('employeeName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-6">
                                <input id="position" type="text" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" name="position" value="{{ $account->position }}" required autofocus>

                                @if ($errors->has('position'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('position') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="idNumber" class="col-md-4 col-form-label text-md-right">{{ __('ID Number') }}</label>

                            <div class="col-md-6">
                                <input id="idNumber" type="text" class="form-control{{ $errors->has('idNumber') ? ' is-invalid' : '' }}" name="idNumber" value="{{ $account->idNumber }}" required>

                                @if ($errors->has('idNumber'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('idNumber') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="licenseNumber" class="col-md-4 col-form-label text-md-right">{{ __('License Number') }}</label>

                            <div class="col-md-6">
                                <input id="licenseNumber" type="text" class="form-control{{ $errors->has('licenseNumber') ? ' is-invalid' : '' }}" name="licenseNumber" value="{{ $account->licenseNumber }}" required>

                                @if ($errors->has('licenseNumber'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('licenseNumber') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="userType" class="col-md-4 col-form-label text-md-right">{{ __('User Type') }}</label>

                            <div class="col-md-6">
                                <select id="userType" type="text" class="form-control{{ $errors->has('userType') ? ' is-invalid' : '' }}" name="userType">
                                    <option value="administrator" @if($account->userType == 'administrator') selected @endif>Admin</option>
                                    <option value="analyst" @if($account->userType == 'analyst') selected @endif>Analyst</option>
                                    <option value="secretary" @if($account->userType == 'secretary') selected @endif>Secretary</option>                                    
                                </select>
                                @if ($errors->has('userType'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('userType') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-md-4 col-md-6">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>

@endsection
