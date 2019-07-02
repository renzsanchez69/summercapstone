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
                    <li class="active">Cart</li>
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
        <div class="row">
            <div class="col-md-7">
                @if ($products->isEmpty())
                <div class="row">
                    <h4>
                        <span class="glyphicon glyphicon-shopping-cart fa-2x" style="color:#D10024"></span>
                        Your cart is empty. <a href="{{ route('home') }}"> Click here to shop now.
                    </h4>
                </div>
                @else
                @foreach ($products as $indexKey => $item )
                <div class="row cart-row" id="cart-page-row-{{$item->id}}">
                    <div class="col-md-6">
                        <div class="row">
                            @if (!$item->images->isEmpty())
                            <img src="{{ url('uploads/'.$item->images[0]->filename) }}" alt="{{ $item->images[0]->filename }}" @else <img src="{{ asset('img/blank.png') }}" alt="blank" @endif class="rounded-circle img-fluid img-thumbnail cart-image" height="200" width="200" />
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number cart-page-btn" data-type="minus" data-field="cart-number-{{ $item->id }}">
                                            <span class="glyphicon glyphicon-minus "></span>
                                        </button>
                                    </span>

                                    <input onkeypress="return false" type="text" name="cart-number-{{ $item->id}}" class="form-control input-number cart-number" value="{{ $item->pivot->qty }}" min="1" max="1000" product_id="{{ $item->id }}" id="cart-number-{{ $item->id}}">

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-number cart-page-btn" data-type="plus" data-field="cart-number-{{ $item->id}}">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default cart-page-btn" onclick="delete_cart_page_item('{{ $item->id }}')">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 cart-item">
                        <div class="row">
                            <h3 class="align-middle">
                                <span class="cart-item-name">
                                    <a href="{{ url('/products/'.$item->id) }}">
                                        {{ $item->name }}
                                    </a>
                                </span>
                            </h3>
                        </div>
                        <div class="row">
                            <h4 class="align-middle">
                                Descripiton:
                            </h4>
                            <p>
                                {{ $item->desc }}
                            </p>
                        </div>
                        <div class="row">
                            <?php
                            require_once '..\app\Utilities\Tools.php';
                            $price = Tools::monetize(true, $item->price);
                            ?>
                            <h4 class="align-middle">
                                Price: K <?php echo $price; ?>
                            </h4>
                        </div>
                        <div class="row">
                            <?php
                            $total = Tools::monetize(true, $product_total[$indexKey]);
                            ?>
                            <h4>
                                Total:
                                <span id="cart-page-product-total-{{$item->id}}">
                                    K <?php echo $total; ?>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="col-md-5 order-details">
                {{-- For future --}}
                {{-- <div class="section-title text-center">
                        <h5 class="title">
                            <span class="glyphicon glyphicon-shopping-cart fa-2x"
                            style="color:#D10024">
                            </span>
                        </h5>
                    </div> --}}
                {{-- For future --}}
                @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
                @endif
                <form method="POST" action="{{ route('updateAddress') }}">
                    @csrf
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-map-marker"></span>
                            </div>
                            <input class="form-control cart-address" id="cart-address" name="address" type="text" value="{{ $address }}" />
                            <span class="input-group-btn">
                                <button class="btn btn-default cart-page-btn" type="submit">Change</button>
                            </span>
                        </div>
                        @if ($errors->has('address'))
                        <span class="invalid-feedback text-danger" role="alert">
                            <strong>{{ $errors->first('address') }}</strong>
                        </span>
                        @endif
                    </div>
                </form>
                <div class="section-title text-center">
                    <h5 class="title">Order Summary</h5>
                </div>

                <div class="order-summary">
                    <div class="order-col">
                        <div>
                            <strong>TOTAL: </strong>
                        </div>
                        <div>
                            <strong class="order-total">
                                <?php
                                require_once '..\app\Utilities\Tools.php';
                                $subtotal = Tools::monetize(true, $product_subtotal);
                                ?>
                                <span id="cart-page-total">K <?php echo $subtotal; ?><span>
                            </strong>
                        </div>
                    </div>

                    <div class="order-col">
                        <div>
                            <strong>Number of items: </strong>
                        </div>
                        <div>
                            <strong class="order-total">
                                <span id="cart-page-items">{{ $cart_items }}<span>
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <a href="/checkout" class="primary-btn order-submit btn-lg btn-block" role="button" aria-pressed="true">
                            Checkout
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection