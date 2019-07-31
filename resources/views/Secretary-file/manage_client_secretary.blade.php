@extends('layouts.secretary_app')

@section('content')
<div class="container">
<div class="alert alert-info">
  <strong>Info!</strong> Ready For Pick Up Clients
  <br>
  <strong>Note:</strong> If button is green, it is already paid/sent. Otherwise, it is not.
</div>
<table class="table">
  <thead class="thead-light">
    <tr>
      <th>RIS Number</th>
      <th>Client ID</th>
      <th>Client Name</th>
      <th>Contact Number</th>
      <th> Ready for Pick Up </>
      <th></th>
    </tr>
    
  @foreach($status as $p)


    <tr>
      <td>{{$p->risNumber}}</td>
      <td>{{$p->clientId}}</td>
      <td>{{$p->nameOfPerson}}</td>
      <td>{{$p->contactNumber}}</td>
      <td>{{$p->readyForPickUp}}</td>
      <td>
      <div class="row">
        <div class="col-md-4">
          <form method="POST" action="{{ route('paidSecretary', [$p->clientId]) }}">
            @csrf
            <button type="submit" class="btn smol btn-sm @if($p->paid == 'yes') btn-success @else btn-primary @endif">@if($p->paid == 'yes') PAID @else UNPAID @endif</button>
          </form>
        </div>
        <div class="col-md-4">
          <form method="POST" action="{{ route('send', [$p->clientId]) }}">
          @csrf
          <button type="submit" class="btn smol btn-sm @if($p->sendText == 'Yes') btn-success @else btn-primary @endif">@if($p->sendText == 'Yes') SENT @else SEND @endif</button>
          </form>
        </div>
        </td>
      </div>
      <div class="offset-md-5 mt-3">
          {{ $status->links() }}
      </div>
    </tr>
</thead>


@endforeach
</table>
</div>
</body>

@endsection