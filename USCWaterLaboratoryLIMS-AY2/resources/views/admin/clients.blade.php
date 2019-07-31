@extends('layouts.admin_app')

@section('content')

{{-- DECLARING OF COUNTER VARIABLE FOR MULTIPLE MODALS --}}
<?php $count = 0; ?>

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
                    Clients
                    &nbsp;
                    <a href="#addClient" id="addNew" class="glyphicon glyphicon-plus" data-toggle="collapse" onclick="changeText()">Add new</a>
                    <form class="float-right" action="{{ route('searchClient-admin') }}" method="GET">
                        @csrf
                        <select class="js-example-responsive" id="search" name="search">
                            <option selected>Search RIS Number</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->risNumber }}">{{ $customer->risNumber }}</option>
                            @endforeach
                        </select>
                        <input class="float-right" type="submit" value="Search">
                    </form>
                    <div id="addClient" @if($errors->any()) class="collapse.show" @else class="collapse" @endif>
                    <div class="card-body">
                            {{-- FORM FOR ADDING CLIENT HERE --}}
                            <form method="POST" action="{{ route('addClient-admin') }}">
                            
                                @csrf
                                <div class="form-group row">
                                    <label for="nameOfPerson" class="col-md-4 col-form-label text-md-right">{{ __('Name of Person') }}</label>
        
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
                                    <label for="nameOfEntity" class="col-md-4 col-form-label text-md-right">{{ __('Name of Entity') }}</label>
        
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
                                    <label for="contactNumber" class="col-md-4 col-form-label text-md-right">{{ __('Mobile Number') }}</label>
        
                                    <div class="col-md-6">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">+63</div>
                                            </div>
                                            <input id="contactNumber" type="text" class="form-control{{ $errors->has('contactNumber') ? ' is-invalid' : '' }}" name="contactNumber" value="{{ old('contactNumber') }}" placeholder="Optional">
                                        </div>
        
                                        @if ($errors->has('contactNumber'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('contactNumber') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="faxNumber" class="col-md-4 col-form-label text-md-right">{{ __('Fax No.') }}</label>
                                    
                                    <div class="col-md-3">
                                        <input id="faxNumber" type="text" class="form-control{{ $errors->has('faxNumber') ? ' is-invalid' : '' }}" name="faxNumber" value="{{ old('faxNumber') }}" placeholder="Optional">
        
                                        @if ($errors->has('faxNumber'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('faxNumber') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <label for="telephone" class="col-form-label text-md-right">{{ __('Telephone') }}</label>

                                    <div class="col-md-3">
                                        <div class="input-group mb-2">
                                            <input id="telephone" type="text" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" name="telephone" value="{{ old('telephone') }}" placeholder="Optional">
                                        </div>
        
                                        @if ($errors->has('telephone'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('telephone') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="emailAddress" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="emailAddress" type="email" class="form-control{{ $errors->has('emailAddress') ? ' is-invalid' : '' }}" name="emailAddress" value="{{ old('emailAddress') }}" placeholder="Optional" autofocus>
        
                                        @if ($errors->has('emailAddress'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('emailAddress') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="discount" class="col-md-4 col-form-label text-md-right">{{ __('Discount') }}</label>
        
                                    <div class="col-md-3">
                                        <div class="input-group mb-2">
                                            <input id="discount" type="number" class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}" name="discount" value="{{ old('discount') }}" placeholder="Optional">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        
        
                                        @if ($errors->has('discount'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('discount') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <label for="deposit" class="col-form-label text-md-right">{{ __('Deposit') }}</label>
                                    <div class="col-md-3">
                                        <input id="deposit" type="number" class="form-control{{ $errors->has('deposit') ? ' is-invalid' : '' }}" name="deposit" value="{{ old('deposit') }}" placeholder="Optional">
        
                                        @if ($errors->has('deposit'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('deposit') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="testResult" class="col-md-4 col-form-label text-md-right">{{ __('Test Result') }}</label>
        
                                    <div class="col-md-2">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="testResult" id="testResult" value="Email" checked autocomplete="off"> Email
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="testResult" id="testResult" value="Fax" autocomplete="off"> Fax
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="testResult" id="testResult" value="LBC" autocomplete="off"> LBC
                                            </label>
                                        </div>
        
                                        @if ($errors->has('testResult'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('testResult') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <label for="reclaimSample" class="col-form-label text-md-right">{{ __('Reclaim Sample') }}</label>
                                    <div class="col-md-2">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="reclaimSample" id="reclaimSample" value="1" checked autocomplete="off"> Yes
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="reclaimSample" id="reclaimSample" value="0" autocomplete="off"> No
                                            </label>
                                        </div>
        
                                        @if ($errors->has('reclaimSample'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('reclaimSample') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="remarks" class="col-md-4 col-form-label text-md-right">{{ __('Remarks') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="remarks" type="text" class="form-control{{ $errors->has('remarks') ? ' is-invalid' : '' }}" name="remarks" value="{{ old('remarks') }}" autofocus>
        
                                        @if ($errors->has('remarks'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('remarks') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="followUp" class="col-md-4 col-form-label text-md-right">{{ __('Follow Up Date') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="followUp" type="datetime-local" class="form-control{{ $errors->has('followUp') ? ' is-invalid' : '' }}" name="followUp" value="{{ old('followUp') }}" required autofocus>
        
                                        @if ($errors->has('followUp'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('followUp') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('Add Client') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                            {{-- END FORM --}}
                        </div>
                    </div>
                </div>
                @if ($clients->count() == 0)
                <div class="alert alert-info m-0" role="alert">
                    <p>You have no clients. Please add a client.</p>
                </div>
                @else
                {{-- TABLE FOR DISPLAYING CLIENTS --}}
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                {{-- TABLE HEADER --}}
                                <th class="admin-table">RIS</th>
                                <th class="admin-table">Name of Person</th>
                                <th class="admin-table">Name of Entity</th>
                                <th class="admin-table">Address</th>
                                <th class="admin-table">Contact No.</th>
                                <th class="admin-table">Fax</th>
                                <th class="admin-table">Email</th>
                                <th class="admin-table">Discount</th>
                                <th class="admin-table">Deposit</th>
                                <th class="admin-table">Test Result</th>
                                <th class="admin-table">Reclaim Sample</th>
                                <th class="admin-table">Remarks</th>
                                <th class="admin-table">Managed By</th>
                                <th class="admin-table">Date Submitted</th>
                                <th class="admin-table">Functions</th>
                                {{-- TABLE HEADER END --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr>
                                {{-- TABLE BODY --}}
                                <td class="admin-table">{{ $client->risNumber }}</td>
                                <td class="admin-table">{{ $client->nameOfPerson }}</td>
                                <td class="admin-table">{{ $client->nameOfEntity }}</td>
                                <td class="admin-table">{{ $client->address }}</td>
                                <td class="admin-table">{{ $client->contactNumber }}</td>
                                <td class="admin-table">{{ $client->faxNumber }}</td>
                                <td class="admin-table">{{ $client->emailAddress }}</td>
                                <td class="admin-table">
                                    @php
                                        if($client->discount < 1){
                                            echo "0%";
                                        }
                                        else {
                                            echo $client->discount.'%';
                                        }
                                    @endphp
                                </td>
                                <td class="admin-table">
                                        @php
                                        if($client->deposit < 1){
                                            echo "0";
                                        }
                                        else {
                                            echo $client->deposit;
                                        }
                                    @endphp
                                </td>
                                <td class="admin-table">{{ $client->testResult }}</td>
                                <td class="admin-table">
                                    @php
                                        if ($client->reclaimSample >= 1){
                                            echo "Yes";
                                        }
                                        else {
                                            echo "No";
                                        }
                                    @endphp
                                </td>
                                <td class="admin-table">{{ $client->remarks }}</td>
                                <td class="admin-table">{{ $client->managedBy }}</td>
                                <td class="admin-table">{{ date("F jS, Y g:m A", strtotime($client->managedDate)) }}</td>
                                <td>
                                    {{-- EDIT BUTTON --}}
                                    <a data-toggle="modal" data-target="#editClient{{ $count }}"><i class="fa fa-edit"></i></a>
                                    <div id="editClient{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header editModal">
                                                    <h5 class="modal-title">Edit Client Information</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="POST" action="{{ route('updateClient-admin', ['clientId' => $client->clientId]) }}">
                                                    @method('PATCH')
                                                    @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label for="nameOfPerson" class="col-md-4 col-form-label text-md-right">{{ __('Name of Person') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="nameOfPerson" type="text" class="form-control{{ $errors->has('nameOfPerson') ? ' is-invalid' : '' }}" name="nameOfPerson" value="{{ $client->nameOfPerson }}" required autofocus>
                            
                                                            @if ($errors->has('nameOfPerson'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('nameOfPerson') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="nameOfEntity" class="col-md-4 col-form-label text-md-right">{{ __('Name of Entity') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="nameOfEntity" type="text" class="form-control{{ $errors->has('nameOfEntity') ? ' is-invalid' : '' }}" name="nameOfEntity" value="{{ $client->nameOfEntity }}" autofocus>
                            
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
                                                            <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $client->address }}" required autofocus>
                            
                                                            @if ($errors->has('address'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('address') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="contactNumber" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>
                                                        @php
                                                            $contact = substr($client->contactNumber, 2); 
                                                        @endphp
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">+63</div>
                                                                </div>
                                                                <input id="contactNumber" type="text" class="form-control{{ $errors->has('contactNumber') ? ' is-invalid' : '' }}" name="contactNumber"  value="{{ $contact }}" required autofocus>
                                                            </div>
                            
                                                            @if ($errors->has('contactNumber'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('contactNumber') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="telephone" class="col-md-4 col-form-label text-md-right">{{ __('Telephone Number') }}</label>
                                                       
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-2">
                                                                <input id="telephone" type="text" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" name="telephone"  value="{{ $client->telephone }}" required autofocus>
                                                            </div>
                            
                                                            @if ($errors->has('telephone'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('telephone') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>  
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <label for="faxNumber" class="col-md-4 col-form-label text-md-right">{{ __('Fax No.') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="faxNumber" type="text" class="form-control{{ $errors->has('faxNumber') ? ' is-invalid' : '' }}" name="faxNumber" value="{{ $client->faxNumber }}">
                            
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
                                                            <input id="emailAddress" type="email" class="form-control{{ $errors->has('emailAddress') ? ' is-invalid' : '' }}" name="emailAddress" value="{{ $client->emailAddress }}" autofocus>
                            
                                                            @if ($errors->has('emailAddress'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('emailAddress') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="discount" class="col-md-4 col-form-label text-md-right">{{ __('Discount') }}</label>
                            
                                                        <div class="col-md-3">
                                                            <input id="discount" type="number" class="form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}" name="discount" value="{{ $client->discount }}">
                            
                                                            @if ($errors->has('discount'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('discount') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                    
                                                        <label for="deposit" class="col-form-label text-md-right">{{ __('Deposit') }}</label>
                                                        <div class="col-md-3">
                                                            <input id="deposit" type="number" class="form-control{{ $errors->has('deposit') ? ' is-invalid' : '' }}" name="deposit" value="{{ $client->deposit }}">
                            
                                                            @if ($errors->has('deposit'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('deposit') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <label for="testResult" class="col-md-4 col-form-label text-md-right">{{ __('Test Result') }}</label>
                            
                                                        <div class="col-md-2">
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="btn btn-secondary btn-sm @if($client->testResult == 'Email') active @endif">
                                                                    <input type="radio" name="testResult" id="testResult" value="Email" @if($client->testResult == 'Email') checked @endif autocomplete="off"> Email
                                                                </label>
                                                                <label class="btn btn-secondary btn-sm @if($client->testResult == 'Fax') active @endif">
                                                                    <input type="radio" name="testResult" id="testResult" value="Fax" @if($client->testResult == 'Fax') checked @endif autocomplete="off"> Fax
                                                                </label>
                                                                <label class="btn btn-secondary btn-sm @if($client->testResult == 'LBC' || $client->testResult == 'lbc') active @endif">
                                                                    <input type="radio" name="testResult" id="testResult" value="LBC" @if($client->testResult == 'LBC' || $client->testResult == 'lbc') checked @endif autocomplete="off"> LBC
                                                                </label>
                                                            </div>
                            
                                                            @if ($errors->has('testResult'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('testResult') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <label for="reclaimSample" class="col-md-4 col-form-label text-md-right">{{ __('Reclaim Sample') }}</label>
                                                        <div class="col-md-6">
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="btn btn-secondary btn-sm @if($client->reclaimSample == 1) active @endif">
                                                                    <input type="radio" name="reclaimSample" id="reclaimSample" value="1" @if($client->reclaimSample == 1) checked @endif autocomplete="off"> Yes
                                                                </label>
                                                                <label class="btn btn-secondary btn-sm @if($client->reclaimSample == 0) active @endif">
                                                                    <input type="radio" name="reclaimSample" id="reclaimSample" value="0" @if($client->reclaimSample == 0) checked @endif autocomplete="off"> No
                                                                </label>
                                                            </div>
                            
                                                            @if ($errors->has('reclaimSample'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('reclaimSample') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="remarks" class="col-md-4 col-form-label text-md-right">{{ __('Remarks') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="remarks" type="text" class="form-control{{ $errors->has('remarks') ? ' is-invalid' : '' }}" name="remarks" value="{{ $client->remarks }}" autofocus>
                            
                                                            @if ($errors->has('remarks'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('remarks') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                            </div> 
                                        </div>
                                    </div>
                                    &nbsp;&nbsp; 
                                    {{-- DELETE BUTTON --}}
                                    <a data-toggle="modal" data-target="#deleteClient{{ $count }}"><i class="fa fa-trash"></i></a>
                                    <div id="deleteClient{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header deleteModal">
                                                    <h5 class="modal-title">Delete Client</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Deleting RIS: {{ $client->risNumber }} will remove other related data (samples).
                                                        <br><br>
                                                        Do you wish to continue?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('deleteClient-admin', [$client->clientId])}}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    {{-- DELETE BUTTON END --}}
                                </td>
                                {{-- TABLE BODY END --}}
                            </tr>
                            {{-- COUNT INCREMENTS --}}
                            <?php $count++; ?>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- TABLE END   --}}
                </div>
                @endif
            </div>
            {{-- PAGINATION LINKS (PAGINATION:6) --}}
            <div class="row justify-content-center mt-2">
                    {{ $clients->links() }}
            </div>
            {{-- PAGINATION END --}}
        </div>
    </div>
</div>

@endsection
