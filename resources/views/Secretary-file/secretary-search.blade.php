@extends('layouts.secretary_app')


@section('content')


<div class="container">
<a href="{{route('form')}}"><button class="btn">  Back</button></a>
<table class="table">
<thead class="thead-light">

  <tr>
    <th>RIS Number</th>
    <th>Client Code</th>
    <th>Client Name</th>
    <th>Name Of Entity</th>
    <th>Address</th>
    <th></th>
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

</div>


</body>

@endsection