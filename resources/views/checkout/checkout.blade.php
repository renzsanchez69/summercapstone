<?php

use App\Http\Controllers\UtilitiesController;
?>
@extends('layouts.base')

@section('content')

@include('layouts.header')
@include('layouts.navigation')

<div id="breadcrumb" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb-tree">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Checkout</li>
                </ul>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            @if (Session::has('success'))
            <div class="alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p>{{ Session::get('success') }}</p>
            </div>
            @endif

            <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                @csrf

                <div class="col-md-7">
                    <!-- Billing Details -->
                    <div class="billing-details">
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name: </label>
                                <input class='form-control' type='text' name="name" value="{{ $name }}" disabled>
                            </div>
                        </div>

                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Adress: </label>
                                <input class='form-control' type='text' name="address" value="{{ $address }}" disabled>
                            </div>
                        </div>

                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>No. of items: </label>
                                <input class='form-control' type='text' name="cart_items" value="{{ $cart_items }}" disabled>
                            </div>
                        </div>

                        <!-- Shiping Details -->
                        <div class="shiping-details">
                            <div class="input-checkbox">
                                <input type="checkbox" id="shiping-address" name="payment_option" class="checkout_payment_check">
                                <label for="shiping-address">
                                    <span></span>
                                    Pay using credit card? Order will be processed automatically as COD if unchecked.
                                </label>

                                <div class="caption">
                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>Billing Adress</label>
                                            <input class='form-control' size='4' type='text' name='billing_address'>
                                            @if ($errors->has('billing_address'))
                                            <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{{ $errors->first('billing_address') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>Name on Card</label>
                                            <input class='form-control' size='4' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group card required'>
                                            <label class='control-label'>Card Number</label>
                                            <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                            <label class='control-label'>CVC</label> <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                                        </div>

                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Month</label>


                                            <!-- <input class='form-control card-expiry-month' placeholder='MM' size='2' type='number' min="1" max="12" pattern="[0-9]"> -->

                                            <select class='form-control card-expiry-month'>
                                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>

                                        </div>

                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Year</label>
                                            <!-- <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='year'> -->

                                            <select class='form-control card-expiry-year'>
                                                <?php

                                                $currentYear = date("Y");

                                                for ($i = 0; $i < 10; $i++) :
                                                    $year = $currentYear + $i;
                                                    ?>
                                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>

                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-md-12 error form-group hide'>
                                            <div class='alert-danger alert'>Please correct the errors and try
                                                again.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Shiping Details -->
                    </div>
                    <!-- /Billing Details -->
                </div>

                <!-- Order Details -->
                <div class="col-md-5 order-details">
                    <div class="section-title text-center">
                        <h3 class="title">Your Order</h3>
                    </div>

                    <div class="order-summary">
                        <div class="order-col">
                            <div><strong>PRODUCT</strong></div>
                            <div><strong>TOTAL</strong></div>
                        </div>

                        <div class="order-products">
                            @foreach ($products as $indexKey => $product)
                            <div class="order-col">
                                <div>
                                    {{ $product->pivot->qty }} x
                                    {{ $product->name }}
                                </div>

                                <div>K {{$product_total[$indexKey]}}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="order-col">
                            <div>Delivery fee</div>
                            <div><strong>15</strong></div>
                        </div>

                        <div class="order-col">
                            <div><strong>TOTAL</strong></div>
                            <?php
                            
                            $orderTotal = UtilitiesController::monetize(true, $product_subtotal);
                            ?>
                            <div><strong class="order-total">K <?php echo $orderTotal; ?></strong></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <button class="primary-btn order-submit btn-lg btn-block" type="submit">Place order</button>
                        </div>
                    </div>
                </div>
                <!-- /Order Details -->
        </div>
        </form>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

@endsection