@extends('layouts.secretary_app')

@section('content')
<div class= "container">
    <div class="col-md-12">
        <div class="alert alert-info">
        <strong> </strong>Select samples that you want to print
        </div>
        <!-- <div class="row">
        @foreach($samples as $sample)
            {{$sample->laboratoryCode}}
            <br>
        @endforeach
        </div> -->
        <form action="{{url('secretary/print')}}" method="POST">
            @csrf
            <div class="form-group row">
                <label for="samples" class="col-md-4 col-form-label text-md-right">{{ __('Samples') }}</label>
                &nbsp;&nbsp;&nbsp;
                <select class="form-control js-example-basic-multiple" style="width:48%;" id="samples" name="samples[]" multiple="multiple">
                    @foreach ($samples as $sample)
                        <option value="{{ $sample->laboratoryCode }}">{{ $sample->laboratoryCode }}</option>
                    @endforeach
                </select>
                @if ($errors->has('samples'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('samples') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-secondary">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </form>
        
    </div>
</div>
</body>
@endsection