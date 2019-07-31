@extends('layouts.admin_app')

@section('content')
{{-- SUCCESS MESSAGE OF DELETING EVENT --}}
    @if(Session::has('flash_event_deleted'))
        <div class="alert alert-info offset-md-1 col-md-10">
            <a class="close" data-dismiss="alert">×</a>
            <strong>Message:</strong> {!!Session::get('flash_event_deleted')!!}
        </div>
    @endif

{{-- DECLARING OF COUNTER VARIABLE FOR MULTIPLE MODALS --}}
<?php $count = 0; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Create Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">View Events</a>
                        </li>
                    </ul>
        
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <form action="{{ route('addEvent-admin') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="eventName" class="offset-md-1 col-md-2 col-form-label">{{ __('Event Name') }}</label>
                    
                                        <div class="col-md-8">
                                            <input id="eventName" type="text" class="form-control{{ $errors->has('eventName') ? ' is-invalid' : '' }}" name="eventName" value="{{ old('eventName') }}" required>
                    
                                            @if ($errors->has('eventName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('eventName') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="startDate" class="offset-md-1 col-md-2 col-form-label">{{ __('Start Date') }}</label>
                    
                                        <div class="col-md-8">
                                            <input id="startDate" type="datetime-local" class="form-control{{ $errors->has('startDate') ? ' is-invalid' : '' }}" name="startDate" required>
                    
                                            @if ($errors->has('startDate'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>The Start Date must be before the End Date</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="endDate" class="offset-md-1 col-md-2 col-form-label">{{ __('End Date') }}</label>
                    
                                        <div class="col-md-8">
                                            <input id="endDate" type="datetime-local" class="form-control{{ $errors->has('endDate') ? ' is-invalid' : '' }}" name="endDate" required>
                    
                                            @if ($errors->has('endDate'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>The end date must be after the Start Date</strong>
                                                </span>
                                            @endif  
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="offset-md-3 col-md-2">
                                            <button type="submit" class="btn btn-secondary btn-sm">
                                                {{ __('Add Event') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                @if ($events->count() == 0)
                                    <div class="alert alert-info m-0" role="alert">
                                        <p>There are no events in your calendar.</p>
                                    </div>
                                @else
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="admin-table">Event</th>
                                            <th class="admin-table">Start Date</th>
                                            <th class="admin-table">End Date</th>
                                            <th class="admin-table">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                        <tr>
                                            <td class="admin-table">{{$event->event_name}}</td>
                                            <td class="admin-table">{{$event->start_date}}</td>
                                            <td class="admin-table">{{$event->end_date}}</td>
                                            <td>
                                                 {{-- EDIT BUTTON --}}
                                                <a data-toggle="modal" data-target="#editEvent{{ $count }}"><i class="fa fa-edit"></i></a>
                                                <div id="editEvent{{ $count }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                            <div class="modal-header editModal">
                                                                <h5 class="modal-title">Edit Event</h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <form action="{{ route('updateEvent-admin', [$event->id]) }}" method="post">
                                                                @method('PATCH')
                                                                @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group row">
                                                                    <label for="eventName" class="col-md-4 col-form-label text-md-right">{{ __('Event Name') }}</label>
                                                                    
                                                                    <div class="col-md-6">
                                                                        <input id="eventName" type="text" class="form-control{{ $errors->has('eventName') ? ' is-invalid' : '' }}" name="eventName" value="{{ $event->event_name }}"  autofocus>
                                                                        
                                                                        @if ($errors->has('eventName'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('eventName') }}</strong>
                                                                        </span>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="startDate" class="col-md-4 col-form-label text-md-right">{{ __('Start Date') }}</label>
                                                                    
                                                                    <div class="col-md-6">
                                                                        <input id="startDate" type="datetime-local" class="form-control{{ $errors->has('startDate') ? ' is-invalid' : '' }}" name="startDate" value="{{ strftime('%Y-%m-%dT%H:%M:%S', strtotime($event->start_date)) }}" autofocus>
                                                                        
                                                                        @if ($errors->has('startDate'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('startDate') }}</strong>
                                                                        </span>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="endDate" class="col-md-4 col-form-label text-md-right">{{ __('End Date') }}</label>
                                                                    
                                                                    <div class="col-md-6">
                                                                        <input id="endDate" type="datetime-local" class="form-control{{ $errors->has('endDate') ? ' is-invalid' : '' }}" name="endDate" value="{{ strftime('%Y-%m-%dT%H:%M:%S', strtotime($event->end_date)) }}"  autofocus>
                                                                        
                                                                        @if ($errors->has('endDate'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('endDate') }}</strong>
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
                                                <a data-toggle="modal" data-target="#deleteEvent{{ $count }}"><i class="fa fa-trash"></i></a>
                                                <div id="deleteEvent{{ $count }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                            <div class="modal-header deleteModal">
                                                                <h5 class="modal-title">Delete Event</h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete Event: {{ $event->event_name }} ?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('destroyEvent-admin', [$event->id])}}" method="post">
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
@endsection