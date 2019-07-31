@extends('layouts.secretary_app')

@section('content')

<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <ul class="secretary-page">
                  <li><a  href="{{url('/secretary/notification')}}">Notification</a></li>
                  <li><a href="{{url('/secretary/inventory')}}">Inventory</a></li>
                  <li class= "active"><a href="{{url('/secretary/view')}}">View Status</a></li>
                  <li><a href="{{url('/secretary/add')}}">Add & View Status</a></li>
                  <li><a href="{{url('/secretary/create')}}">Create Report</a></li>
                </ul>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif                  
                </div>
            Status
            </div>
        </div>
    </div>
</div> -->

@endsection