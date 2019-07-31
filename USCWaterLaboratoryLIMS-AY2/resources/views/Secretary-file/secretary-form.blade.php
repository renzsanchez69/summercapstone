@extends('layouts.secretary_app')


@section('content')
@if(Session::has('flash_client_message'))
<div class="alert alert-info offset-md-1 col-md-10">
    <a class="close" data-dismiss="alert">×</a>
    <strong>Notification:</strong> {!!Session::get('flash_client_message')!!}
</div>
@endif  
@if(Session::has('no_message'))
<div class="alert alert-danger offset-md-1 col-md-10">
    <a class="close" data-dismiss="alert">×</a>
    <strong>Notification:</strong> {!!Session::get('no_message')!!}
</div>
@endif

<div class="container">
  <a href="{{url('secretary/samples')}}">Sample</a>
  <div class="float-right">
    <form method="POST" action="{{ route('search-barcode') }}">
      @csrf
      <input type="text" placeholder="Search client RIS" name="search">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>

<table class="table">
<thead class="thead-light">
  <tr>
  
    <th>RIS Number</th>
    <th>Client Name</th>
    
    <th>Name Of Entity</th>
    <th>Contact Number</th>
    <th>Address</th>
    <th>Print</th>
  </tr>
  @foreach($clients as $client)


  <tr>
  <td><button type="button" class="dropdown-item" data-toggle="modal" data-target="#message{{$client->nameOfPerson}}">
    <label for="message"></label><i class="fa fa-envelope" id="message"></i>&nbsp; {{$client->risNumber}} 
  </button></td>
  <td>{{$client->nameOfPerson}}</td>
  <td>{{$client->nameOfEntity}}</td>
  <td>{{$client->contactNumber}}</td>
  <td>{{$client->address}}</td>
  <td>
 
  
 
  <a href="{{route('barcode',[$client->clientId])}}"  class="btn btn-primary"> <i class="fa fa-print"></i> Print</a>
  

  
  <div class="modal fade" id="message{{$client->nameOfPerson}}" tabindex="-1" role="dialog" aria-hidden="true">
      <form action="{{url('/secretary/informClient/'.$client->clientId)}}" method="POST">
      @csrf
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{$client->risNumber}}  {{$client->nameOfPerson}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <label for="message">Message</label>
            <input class="form-control input-sm" name="message" type="text">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Send</button>
            </div>
          </div>
        </div>
      </form>
    </div>

  </td>
  
  </tr>
  
  @endforeach
  </thead>
</table>

<div class="row justify-content-center">
{{$clients->links()}}
</div>



</div>

</body>

@endsection