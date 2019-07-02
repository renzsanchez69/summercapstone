@extends('layouts.base')

@section('content')
@include('layouts.header')

<?php

foreach ($products as $product) {

    ?>
    <div class="container">

        <form method="POST" action="{{ route('products.update', $product) }}">

            @csrf
            {{ method_field('patch') }}

            @if (Session::has('success'))
            <div class="alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p>{{ Session::get('success') }}</p>
            </div>
            @endif

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="productName" name="productName" value="{{ $product->name}}">
                @if ($errors->has('name'))
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group">
                <label for="username">Price</label>
                <input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" id="productPrice" name="productPrice" value="{{ $product->price}}">
                @if ($errors->has('username'))
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group">
                <label for="username">Quantity</label>
                <input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" id="productQuantity" name="productQuantity" value="{{ $product->qty}}">
                @if ($errors->has('username'))
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
                @endif
            </div>


            <div class="row">
                <div class="col-xs-2 col-xs-offset-10">
                    <button type="submit" class="btn btn-primary btn-small btn-block">Update</button>
                </div>
            </div>
        </form>
    </div>

<?php } ?>
@endsection