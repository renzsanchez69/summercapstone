@extends('layouts.admin_app')

@section('content')

{{-- DECLARING OF COUNTER VARIABLE FOR MULTIPLE MODALS --}}
<?php $count = 0; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Accounts
                    &nbsp;
                    <a href="#addAccount" id="addNew" class="glyphicon glyphicon-plus" data-toggle="collapse" onclick="changeText()">Add new</a>
                    <form class="float-right" action="{{ route('searchAccount-admin') }}" method="GET">
                            @csrf
                            <select class="js-example-responsive" id="search" name="search">
                                <option selected>Search Employee Name</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employeeName }}">{{ $employee->employeeName }}</option>
                                @endforeach
                            </select>
                            <input class="float-right" type="submit" value="Search">
                        </form>
                <div id="addAccount" @if($errors->any()) class="collapse.show" @else class="collapse" @endif>
                    <div class="card-body">
                            <form method="POST" action="{{ route('addAccount-admin') }}">
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
        
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="employeeName" class="col-md-4 col-form-label text-md-right">{{ __('Employee Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="employeeName" type="text" class="form-control{{ $errors->has('employeeName') ? ' is-invalid' : '' }}" name="employeeName" value="{{ old('employeeName') }}" required autofocus>
        
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
                                        <input id="position" type="text" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" name="position" required autofocus>
        
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
                                        <input id="idNumber" type="text" class="form-control{{ $errors->has('idNumber') ? ' is-invalid' : '' }}" name="idNumber" required>
        
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
                                        <input id="licenseNumber" type="text" class="form-control{{ $errors->has('licenseNumber') ? ' is-invalid' : '' }}" name="licenseNumber" required>
        
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
                                            <option value="administrator">Admin</option>
                                            <option value="analyst">Analyst</option>
                                            <option value="secretary">Secretary</option>                                    
                                        </select>
                                        @if ($errors->has('userType'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('userType') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('Add Account') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @if ($accounts->count() == 0)
                    <div class="alert alert-info m-0" role="alert">
                        <p>There are no accounts found. Please add a new account.</p>
                    </div>
                @else
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="admin-table">Employee Name</th>
                                <th class="admin-table">Username</th>
                                <th class="admin-table">Position</th>
                                <th class="admin-table">ID No.</th>
                                <th class="admin-table">License No.</th>
                                <th class="admin-table">Updated By</th>
                                <th class="admin-table">Updated At</th>
                                <th class="admin-table">Functions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                            <tr>
                                <td class="admin-table">{{ $account->employeeName }}</td>
                                <td class="admin-table">{{ $account->username }}</td>
                                <td class="admin-table">{{ $account->userType }}</td>
                                <td class="admin-table">{{ $account->idNumber }}</td>
                                <td class="admin-table">{{ $account->licenseNumber }}</td>
                                <td class="admin-table">{{ $account->managedBy }}</td>
                                <td class="admin-table">{{ date("F jS, Y H:m", strtotime($account->managedDate)) }}</td>
                                <td>
                                    {{-- EDIT BUTTON --}}
                                    <a data-toggle="modal" data-target="#editAccount{{ $count }}"><i class="fa fa-edit"></i></a>
                                    <div id="editAccount{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header editModal">
                                                    <h5 class="modal-title">Edit Account</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                    <form method="POST" action="{{ route('updateAccount-admin', [$account->employeeId])}}">
                                                        @method('PATCH')
                                                        @csrf
                                                    <div class="modal-body">
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
                                
                                                        {{-- <div class="form-group row">
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
                                                        </div> --}}
                                
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
                                    <a data-toggle="modal" data-target="#deleteAccount{{ $count }}"><i class="fa fa-trash"></i></a>
                                    <div id="deleteAccount{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header deleteModal">
                                                    <h5 class="modal-title">Delete Account</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                <p>Are you sure you want to delete {{ $account->employeeName }} account?</p>                          
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('deleteAccount-admin', [$account->employeeId]) }}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                            
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            {{-- COUNT INCREMENTS --}}
                            <?php $count++; ?>
                            @endforeach
                        </tbody>
                    </table>           
                </div>
                @endif
            </div>
            <div class="row justify-content-center mt-2">
                    {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
