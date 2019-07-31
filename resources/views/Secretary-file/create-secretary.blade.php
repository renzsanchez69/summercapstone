@extends('layouts.secretary_app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" >
                    Add Client
                </div>
                <div class="card-body">
                            <form method="POST" style="margin-top:40px" action="{{ route('addClient-secretary') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="nameOfPerson" class="col-md-4 col-form-label text-md-right">{{ __('Client Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nameOfPerson" type="text" class="form-control{{ $errors->has('nameOfPerson') ? ' is-invalid' : '' }}" name="nameOfPerson" value="{{ old('nameOfPerson') }}" required autofocus>
        
                                        @if ($errors->has('nameOfPerson'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nameOfPerson') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="nameOfEntity" class="col-md-4 col-form-label text-md-right">{{ __('Entity Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nameOfEntity" type="text" class="form-control{{ $errors->has('nameOfEntity') ? ' is-invalid' : '' }}" name="nameOfEntity" value="{{ old('nameOfEntity') }}" placeholder="Optional" autofocus>
        
                                        @if ($errors->has('nameOfEntity'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nameOfEntity') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" required autofocus>
        
                                        @if ($errors->has('address'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="contactNumber" class="col-md-4 col-form-label text-md-right">{{ __('Contact Number') }}</label>
        
                                    <div class="col-md-3">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">+63</div>
                                            </div>
                                            <input id="contactNumber" type="text" maxlength="10" class="form-control{{ $errors->has('contactNumber') ? ' is-invalid' : '' }}" name="contactNumber" value="{{ old('contactNumber') }}" required autofocus>
                                        </div>

                                        @if ($errors->has('contactNumber'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('contactNumber') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="faxNumber" class="col-md-4 col-form-label text-md-right">{{ __('Fax') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="faxNumber" type="number" class="form-control{{ $errors->has('faxNumber') ? ' is-invalid' : '' }}" name="faxNumber" value="{{ old('faxNumber') }}" required autofocus>
        
                                        @if ($errors->has('faxNumber'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('faxNumber') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="emailAddress" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="emailAddress" type="email" class="form-control{{ $errors->has('emailAddress') ? ' is-invalid' : '' }}" name="emailAddress" value="{{ old('emailAddress') }}" placeholder= "Optional" autofocus>
        
                                        @if ($errors->has('emailAddress'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('emailAddress') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                  
                                <div class="form-group row">

                                    <label for="reclaimSample" class="col-md-4 col-form-label text-md-right">{{ __('Reclaim Sample') }}</label>
                                    <div class="col-md-1 col-form-label text-md-right">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary">
                                                <input value="1" type="radio" name="reclaimSample" id="reclaimSample" autocomplete="off"> Yes
                                            </label>
                                            <label class="btn btn-secondary  active">
                                                <input value="0" type="radio" name="reclaimSample" id="reclaimSample" autocomplete="off"  checked> No
                                            </label>
                                        </div>
                                    </div>


                                    <label for="testResult" class="col-md-1 col-form-label">{{ __('Test Result') }}</label>
                                    <div class="col-md-1 col-form-label text-md-right">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary">
                                                    <input value="email" type="radio" name="testResult" id="testResult" autocomplete="off"> Email
                                                </label>

                                                <label class="btn btn-secondary">
                                                    <input value="fax" type="radio" name="testResult" id="testResult" autocomplete="off"> Fax
                                                </label>

                                                <label class="btn btn-secondary">
                                                    <input value="lbc" type="radio" name="testResult" id="testResult" autocomplete="off"> LBC
                                                </label>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label for="discount" class="col-md-4 col-form-label text-md-right">{{ __('Discount') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="discount" type="number" class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}" name="discount" placeholder=Optional autofocus>
        
                                        @if ($errors->has('discount'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('discount') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="deposit" class="col-md-4 col-form-label text-md-right">{{ __('Deposit') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="deposit" type="number" class="form-control{{ $errors->has('deposit') ? ' is-invalid' : '' }}" name="deposit" placeholder=Optional autofocus>
        
                                        @if ($errors->has('deposit'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('deposit') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="remarks" class="col-md-4 col-form-label text-md-right">{{ __('Remarks') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="remarks" type="text" class="form-control{{ $errors->has('sampleMatrix') ? ' is-invalid' : '' }}" name="remarks" required autofocus>
        
                                        @if ($errors->has('remarks'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('remarks') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="dueDate" class="col-md-4 col-form-label text-md-right">{{ __('Follow Up Date') }}</label>
        
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="dueDate" id="dueDate" class="form-control{{ $errors->has('dueDate') ? ' is-invalid' : '' }}" value="{{ old('dueDate') }}" required>
                                        @if ($errors->has('dueDate'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('dueDate') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('Create') }}
                                        </button>
                                    </div>
                                </div> 
                            </form>
                        </div>
                </div>
            </div>
     </div>
</div>

@endsection