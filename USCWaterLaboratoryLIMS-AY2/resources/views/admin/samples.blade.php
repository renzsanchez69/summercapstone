@extends('layouts.admin_app')

@section('content')

{{-- DECLARING OF COUNTER VARIABLE FOR MULTIPLE MODALS --}}
<?php $count = 0; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Samples
                    &nbsp;
                    <a href="#addSample" id="addNew" class="glyphicon glyphicon-plus" data-toggle="collapse" onclick="changeText()">Add new</a>
                    <form class="float-right" action="{{ route('searchSample-admin') }}" method="GET">
                        @csrf
                        <select class="js-example-responsive" id="search" name="search">
                            <option selected>Search Lab Code</option>
                            @foreach ($samps as $samp)
                                <option value="{{ $samp->laboratoryCode }}">{{ $samp->laboratoryCode }}</option>
                            @endforeach
                        </select>
                        <input class="float-right" type="submit" value="Search">
                    </form>
                    <div id="addSample" @if($errors->any()) class="collapse.show" @else class="collapse" @endif>
                        <div class="card-body">
                            <form action="{{ route('insertSample-admin') }}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label for="clientId" class="col-md-4 col-form-label text-md-right">{{ __('Client RIS') }}</label>
                                    
                                    <div class="col-md-6">
                                        <select class="form-control" id="clientId" name="clientId" required autofocus>
                                                <option value="" selected>SELECT A CLIENT</option>
                                                @if((old('clientId')) != NULL)
                                                    @foreach($clients as $client)
                                                        <option value="{{ $client->risNumber }}" @if($client->risNumber == old('clientId')) selected @endif>{{ $client->risNumber }} - {{ $client->nameOfPerson }}</option>
                                                    @endforeach
                                                @endif
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->risNumber }}" @if($client->clientId == old('clientId')) selected @endif>{{ $client->risNumber }} - {{ $client->nameOfPerson }}</option>
                                            @endforeach
                                        </select>
        
                                        @if ($errors->has('clientId'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('clientId') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="clientsCode" class="col-md-4 col-form-label text-md-right">{{ __('Client Code') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="clientsCode" type="text" class="form-control{{ $errors->has('clientsCode') ? ' is-invalid' : '' }}" name="clientsCode" value="{{ old('clientsCode') }}" required autofocus>
        
                                        @if ($errors->has('clientsCode'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('clientsCode') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="sampleType" class="col-md-4 col-form-label text-md-right">{{ __('Sample Type') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="sampleType" list="sampleTypes" class="form-control{{ $errors->has('sampleType') ? ' is-invalid' : '' }}" name="sampleType" value="{{ old('sampleType') }}" required autofocus>
                                        <datalist id="sampleTypes">
                                            <option value="Drinking water">Drinking Water</option>
                                            <option value="Ground water">Ground water</option>
                                            <option value="Waste water">Waste water</option>
                                        </datalist>
                                        @if ($errors->has('sampleType'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('sampleType') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="sampleCollection" class="col-md-4 col-form-label text-md-right">{{ __('Collection Time') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="sampleCollection" type="datetime-local" class="form-control{{ $errors->has('sampleCollection') ? ' is-invalid' : '' }}" name="sampleCollection" value="{{ old('sampleCollection') }}" required autofocus>
        
                                        @if ($errors->has('sampleCollection'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('sampleCollection') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="samplePreservation" class="col-md-4 col-form-label text-md-right">{{ __('Sample Preservation') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="samplePreservation" type="text" class="form-control{{ $errors->has('samplePreservation') ? ' is-invalid' : '' }}" name="samplePreservation" value="{{ old('samplePreservation') }}" placeholder="Optional" autofocus>
        
                                        @if ($errors->has('samplePreservation'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('samplePreservation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="parameter" class="col-md-4 col-form-label text-md-right">{{ __('Parameter Requested') }}</label>
                                    &nbsp;&nbsp;&nbsp;
                                    <select class="form-control js-example-basic-multiple" style="width:48%;" id="parameter" name="parameter[]" multiple="multiple">
                                        @if(is_array(old('parameter')))
                                            @foreach($parameters as $parameter)
                                                <option value="{{ $parameter->analysis }}" @if(in_array($parameter->analysis, old('parameter'))) selected @endif>{{$parameter->analysis}}</option>
                                            @endforeach
                                        @endif
                                        @foreach ($parameters as $parameter)
                                            <option value="{{ $parameter->analysis }}">{{ $parameter->analysis }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('parameter'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('parameter') }}</strong>
                                        </span>
                                    @endif
                                </div>
        
                                <div class="form-group row">
                                    <label for="purposeOfAnalysis" class="col-md-4 col-form-label text-md-right">{{ __('Purpose of Analysis') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="purposeOfAnalysis" list="purposeOfAnalyses" class="form-control{{ $errors->has('purposeOfAnalysis') ? ' is-invalid' : '' }}" name="purposeOfAnalysis" value="{{ old('purposeOfAnalysis') }}" placeholder="Optional" autofocus>
                                        <datalist id="purposeOfAnalyses">
                                            <option value="Business">Business</option>
                                            <option value="DA">DA</option>
                                            <option value="Economic">Economic</option>
                                            <option value="Health Related">Health Related</option>
                                            <option value="Quality">Quality</option>
                                            <option value="Regulating Protocols">Regulating Protocols</option>
                                            <option value="Research">Research</option>
                                        </datalist>
                                        @if ($errors->has('purposeOfAnalysis'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('purposeOfAnalysis') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="sampleSource" class="col-md-4 col-form-label text-md-right">{{ __('Sample Source') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="sampleSource" type="text" class="form-control{{ $errors->has('sampleSource') ? ' is-invalid' : '' }}" name="sampleSource" value="{{ old('sampleSource') }}" required autofocus>
        
                                        @if ($errors->has('sampleSource'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('sampleSource') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="dueDate" class="col-md-4 col-form-label text-md-right">{{ __('Due Date') }}</label>
        
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
                                            {{ __('Add Sample') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @if ($samples->count() == 0)
                <div class="alert alert-info m-0" role="alert">
                    <p>There are no samples added to any client. Please add a sample.</p>
                </div>
                @else
                <div class="card-body">
                    <table id="sample-table" class="table">
                        <thead>
                            <tr>
                                <th class="admin-table">RIS</th>
                                <th class="admin-table">Lab Code</th>
                                <th class="admin-table">Client's Code</th>
                                <th class="admin-table">Sample Type</th>
                                <th class="admin-table">Sample Collection</th>
                                <th class="admin-table">Sample Preservation</th>
                                <th class="admin-table">Purpose of Analysis</th>
                                <th class="admin-table">Sample Source</th>
                                <th class="admin-table">Due Date</th>
                                <th class="admin-table">Functions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($samples as $sample)
                            <tr>
                                <td class="admin-table">{{ $sample->client->risNumber }}</td>
                                <td class="admin-table">{{ $sample->laboratoryCode }}</td>
                                <td class="admin-table">{{ $sample->clientsCode }}</td>
                                <td class="admin-table">{{ $sample->sampleType }}</td>
                                <td class="admin-table">{{ $sample->sampleCollection }}</td>
                                <td class="admin-table">
                                    @if($sample->samplePreservation != NULL)
                                        {{ $sample->samplePreservation }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="admin-table">
                                    @if($sample->purposeOfAnalysis != NULL)
                                        {{ $sample->purposeOfAnalysis }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="admin-table">{{ $sample->sampleSource }}</td>
                                <td class="admin-table">{{ $sample->dueDate }}</td>
                                <td>
                                    {{-- EDIT BUTTON --}}
                                    <a data-toggle="modal" data-target="#editSample{{ $count }}"><i class="fa fa-edit"></i></a>
                                    <div id="editSample{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header editModal">
                                                    <h5 class="modal-title">Edit Sample</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form action="{{ route('updateSample-admin', ['sampleId' => $sample->sampleId]) }}" method="post">
                                                    @method('PATCH')
                                                    @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label for="clientId" class="col-md-4 col-form-label text-md-right">{{ __('Client RIS') }}</label>
                                                        
                                                        <div class="col-md-6">
                                                            <input id="clientId" type="text" class="form-control{{ $errors->has('clientId') ? ' is-invalid' : '' }}" name="clientId" value="{{ $sample->client->risNumber }}" placeholder="xxxx-xxxx" required autofocus>
                            
                                                            @if ($errors->has('clientId'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('clientId') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="clientsCode" class="col-md-4 col-form-label text-md-right">{{ __('Client Code') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="clientsCode" type="text" class="form-control{{ $errors->has('clientsCode') ? ' is-invalid' : '' }}" name="clientsCode" value="{{ $sample->clientsCode }}" required autofocus>
                            
                                                            @if ($errors->has('clientsCode'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('clientsCode') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="sampleType" class="col-md-4 col-form-label text-md-right">{{ __('Sample Type') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="sampleType" list="sampleTypes" class="form-control{{ $errors->has('sampleType') ? ' is-invalid' : '' }}" name="sampleType" value="{{ $sample->sampleType }}" required autofocus>
                                                            <datalist id="sampleTypes">
                                                                <option value="Drinking water">Drinking Water</option>
                                                                <option value="Ground water">Ground water</option>
                                                                <option value="Waste water">Waste water</option>
                                                            </datalist>
                                                            @if ($errors->has('sampleType'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('sampleType') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="sampleCollection" class="col-md-4 col-form-label text-md-right">{{ __('Collection Time') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="sampleCollection" type="datetime-local" class="form-control{{ $errors->has('sampleCollection') ? ' is-invalid' : '' }}" name="sampleCollection" value="{{ strftime('%Y-%m-%dT%H:%M:%S', strtotime($sample->sampleCollection)) }}" required autofocus>
                            
                                                            @if ($errors->has('sampleCollection'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('sampleCollection') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="samplePreservation" class="col-md-4 col-form-label text-md-right">{{ __('Sample Preservation') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="samplePreservation" type="text" class="form-control{{ $errors->has('samplePreservation') ? ' is-invalid' : '' }}" name="samplePreservation" value="{{ $sample->samplePreservation }}" placeholder="Optional" autofocus>
                            
                                                            @if ($errors->has('samplePreservation'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('samplePreservation') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <label for="newParameter{{ $count }}" class="col-md-4 col-form-label text-md-right">{{ __('Parameter Requested') }}</label>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <select class="js-example-basic-multiple form-control" style="width:48%;" id="newParameter{{ $count }}" name="newParameter[]" multiple="multiple">
                                                            @foreach ($parameters as $parameter)
                                                                <option value="{{ $parameter->analysis }}">{{ $parameter->analysis }}</option>
                                                            @endforeach
                                                           @foreach ($sample->parameters as $param)
                                                                <option value="{{ $param->analysis }}" selected>{{ $param->analysis }}</option>
                                                           @endforeach
                                                        </select>
                                                        @if ($errors->has('newParameter'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('newParameter') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="purposeOfAnalysis" class="col-md-4 col-form-label text-md-right">{{ __('Purpose of Analysis') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="purposeOfAnalysis" list="purposeOfAnalyses" class="form-control{{ $errors->has('purposeOfAnalysis') ? ' is-invalid' : '' }}" name="purposeOfAnalysis" value="{{ $sample->purposeOfAnalysis }}" placeholder="Optional" autofocus>
                                                            <datalist id="purposeOfAnalyses">
                                                                <option value="Business">Business</option>
                                                                <option value="DA">DA</option>
                                                                <option value="Economic">Economic</option>
                                                                <option value="Health Related">Health Related</option>
                                                                <option value="Quality">Quality</option>
                                                                <option value="Regulating Protocols">Regulating Protocols</option>
                                                                <option value="Research">Research</option>
                                                            </datalist>
                                                            @if ($errors->has('purposeOfAnalysis'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('purposeOfAnalysis') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="sampleSource" class="col-md-4 col-form-label text-md-right">{{ __('Sample Source') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="sampleSource" type="text" class="form-control{{ $errors->has('sampleSource') ? ' is-invalid' : '' }}" name="sampleSource" value="{{ $sample->sampleSource }}" required autofocus>
                            
                                                            @if ($errors->has('sampleSource'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('sampleSource') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                            
                                                    <div class="form-group row">
                                                        <label for="dueDate" class="col-md-4 col-form-label text-md-right">{{ __('Due Date') }}</label>
                                                                               
                                                        <div class="col-md-6">
                                                            <input type="datetime-local" name="dueDate" id="dueDate" class="form-control{{ $errors->has('dueDate') ? ' is-invalid' : '' }}" value="{{ strftime('%Y-%m-%dT%H:%M:%S', strtotime($sample->dueDate)) }}" required>
                                                            @if ($errors->has('dueDate'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('dueDate') }}</strong>
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
                                    <a data-toggle="modal" data-target="#deleteSample{{ $count }}"><i class="fa fa-trash"></i></a>
                                    <div id="deleteSample{{ $count }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header deleteModal">
                                                    <h5 class="modal-title">Delete Sample</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete Sample: {{ $sample->laboratoryCode }} ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('destroySample-admin', [$sample->sampleId])}}" method="post">
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
            <div class="offset-md-5 mt-3">
                {{ $samples->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
