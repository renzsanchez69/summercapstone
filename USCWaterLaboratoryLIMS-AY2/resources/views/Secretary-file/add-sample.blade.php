@extends('layouts.secretary_app')

@section('content')
{{-- SUCCESS MESSAGE OF INSERTING SAMPLE --}}
@if(Session::has('flash_sample_added'))
<div class="alert alert-info offset-md-1 col-md-10">
    <a class="close" data-dismiss="alert">×</a>
    <strong>Notification:</strong> {!!Session::get('flash_sample_added')!!}
</div>
@endif  
{{-- SUCCESS MESSAGE OF ADDING CLIENT --}}

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


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Add Samples to Client
                    <a href="{{ route('addClient-secretary') }}" class="glyphicon glyphicon-plus float-right">Back</a>
                </div>   
                <div class="card-body">
                    <form action="{{ route('postAddSample') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label for="clientId" class="col-md-4 col-form-label text-md-right">{{ __('Client') }}</label>
                            
                            <div class="col-md-6">
                                <select class="form-control" id="clientId" name="clientId" required autofocus>
                                        <option value="" selected>SELECT A CLIENT</option>
                                    @if((old('clientId')) != NULL)
                                        @foreach($clients as $client)
                                            <option value="{{ $client->risNumber }}" @if($client->risNumber == old('clientId')) selected @endif>{{ $client->risNumber }} - {{ $client->nameOfPerson }}</option>
                                        @endforeach
                                    @endif
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->risNumber }}">{{ $client->risNumber }} - {{ $client->nameOfPerson }}</option>
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
                            <select class="form-control js-example-basic-multiple" style="width:48%;" id="parameter" name="parameter[]" multiple="multiple" required>
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
                                    <option value="Economic">Economic</option>
                                    <option value="Regulating Protocols">Regulating Protocols</option>
                                    <option value="Health Related">Health Related</option>
                                    <option value="Research">Research</option>
                                    <option value="DA">DA</option>
                                    <option value="Quality">Quality</option>
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
    </div>
</div>
@endsection