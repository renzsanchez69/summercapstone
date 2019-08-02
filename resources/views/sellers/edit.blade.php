@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-md-12" <br />
    <h3>Edit Record</h3>
    <br />
    @if(count($errors)) >0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all()as $errors)
            <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
        <form method="post" action="{{action('SellerController@update',$id)}}">
            {{csrf_field()}}
            <input type="hidden" name="method" value="PATCH" />
            <div class="form-group">
                <input type="text" name="name" class="form-control" value="{{$products->name}}" placeholder="Enter last name" /> 4
            </div>
            <div class="form-group">
                <input type="text" name="price" class="form-control" value="{{$products->price}}" placeholder="Enter last name" /> 4
            </div>
            <div class="form-group">
                <input type="text" name="qty" class="form-control" value="{{$products->qty}}" placeholder="Enter last name" /> 4
            </div>
            <div class="form-group">
                <input type="text" name="category" class="form-control" value="{{$products->category}}" placeholder="Enter last name" /> 4
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="edit">
            </div>
        </form>
    </div>
</div> @endsection