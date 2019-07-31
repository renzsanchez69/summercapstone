@extends('layouts.secretary_app')


@section('content')


<div class="container">
<a href="{{route('form')}}"><button class="btn"> Back</button></a>
@if(Session::has('flash_not_found'))
<div class="alert alert-danger col-md-12">
    <a class="close" data-dismiss="alert">×</a>
    <strong>Notification:</strong> {!!Session::get('flash_not_found')!!}
</div>
@endif
</div>


</body>

@endsection