@extends('layouts.admin_app')

@section('content')
@if (\Session::has('has_parameters'))
    <div class="alert alert-danger offset-md-1 col-md-10">
        <a class="close" data-dismiss="alert">×</a>
        <strong>Error:</strong> {!!Session::get('has_parameters')!!}
    </div>
@endif
{{-- SUCCESS MESSAGE OF ADDING STATION --}}
    @if(Session::has('flash_station_added'))
        <div class="alert alert-info offset-md-1 col-md-10">
            <a class="close" data-dismiss="alert">×</a>
            <strong>Notification:</strong> {!!Session::get('flash_station_added')!!}
        </div>
    @endif
{{-- SUCCESS MESSAGE OF DELETING STATION --}}
    @if(Session::has('flash_station_deleted'))
        <div class="alert alert-info offset-md-1 col-md-10">
            <a class="close" data-dismiss="alert">×</a>
            <strong>Notification:</strong> {!!Session::get('flash_station_deleted')!!}
        </div>
    @endif
{{-- VALIDATION CHECKS --}}
    @if ($errors->any())
    <div class="alert alert-danger pb-0 offset-md-1 col-md-10">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
                <p>Please try again.</p>
        </ul>
    </div>
    @endif
{{-- DECLARING OF COUNTER VARIABLE FOR MULTIPLE MODALS --}}
<?php $count = 0; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Stations
                    &nbsp;
                    <a href="#addStation" id="addNew" class="glyphicon glyphicon-plus" data-toggle="collapse" onclick="changeText()">Add new</a>
                <div id="addStation" @if($errors->any()) class="collapse.show" @else class="collapse" @endif>
                    <div class="card-body">
                            <form method="POST" action="{{ route('addStation-admin') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="stationName" class="col-md-4 col-form-label text-md-right">{{ __('Station Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="stationName" type="text" class="form-control{{ $errors->has('stationName') ? ' is-invalid' : '' }}" name="stationName" value="{{ old('stationName') }}" required autofocus>
        
                                        @if ($errors->has('stationName'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('stationName') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('Add Station') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @if ($stations->count() == 0)
                    <div class="alert alert-info m-0" role="alert">
                        <p>There are no stations available.</p>
                    </div>
                @else
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="admin-table">Station Name</th>
                                <th class="admin-table">Updated By</th>
                                <th class="admin-table">Updated At</th>
                                <th class="admin-table">Functions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stations as $station)
                            <tr>
                                <td class="admin-table">{{ $station->stationName }}</td>
                                <td class="admin-table">{{ $station->managedBy }}</td>
                                <td class="admin-table">{{ $station->managedDate }}</td>
                                <td>
                                    {{-- DELETE BUTTON --}}
                                    <a data-toggle="modal" data-target="#destroyStation{{ $count }}"><i class="fa fa-trash"></i></a>
                                    <div id="destroyStation{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header deleteModal">
                                                    <h5 class="modal-title">Delete Station</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                <p>Are you sure you want to delete {{ $station->stationName }} station?</p>                          
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('destroyStation-admin', [$station->stationId]) }}" method="post">
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
                    {{ $stations->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
