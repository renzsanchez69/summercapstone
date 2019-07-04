<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Orders;
use App\Products;
use App\User;
use App\Http\Requests;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Redirect;
use Session;
use Stripe\Error\Card;
use Validator;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cart = Auth::user()->cart;
        $products = $cart->products;
        $product_subtotal = 0.00;
        $product_total = [];
        $name = Auth::user()->name;
        $address = Auth::user()->address;
        $cart_items = 0;

        foreach ($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
            $cart_items += $product->pivot->qty;
            array_push(
                $product_total,
                ($product->pivot->qty * $product->price)
            );
        }

        return view('checkout.checkout', [
            'products' => $products,
            'product_subtotal' => $product_subtotal,
            'product_total' => $product_total,
            'name' => $name,
            'address' => $address,
            'cart_items' => $cart_items
        ]);
    }


    /**
     * Get a validator for an incoming order request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'address' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Show the payment page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function payment(Request $request)
    {
        $this->validator($request->all())->validate();

        $cart = Auth::user()->cart;
        $products = $cart->products;
        $product_subtotal = 0.00;
        $product_total = [];
        $shipping_address = $request->address;

        foreach ($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
        }

        return view(
            'checkout.payment',
            [
                'shipping_address' => $shipping_address,
                'product_subtotal' => $product_subtotal
            ]
        );
    }

    /**
     * Post stripe payment or create COD order
     *
     * @return ajax response
     */
    public function stripePost(Request $request)
    {
        $cod_bool = false;
        if ($request->payment_option == null) {
            $cod_bool = true;
        }

        $cart = Auth::user()->cart;
        $products = $cart->products;
        $product_subtotal = 0.00;
        $product_total = [];

        foreach ($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
        }

        if ($product_subtotal == 0.00) {
            Session::put('error', 'No items on cart!');
            return back();
        }

        $order = new Orders;
        $order->order_date = Carbon::now();
        $order->total = $product_subtotal;
        $order->address = Auth::user()->address;
        $order->user_id = Auth::user()->id;

        if (!$cod_bool) {
            $this->validate(request(), [
                'billing_address' => ['required', 'string', 'max:255'],
            ]);

            $stripe = Stripe::make(env('STRIPE_SECRET'));
            try {
                $charge = $stripe
                    ->charges()
                    ->create([
                        'source' => $request->stripeToken,
                        'currency' => 'PGK',
                        'amount' => $product_subtotal,
                        'description' => 'Add in wallet',
                    ]);

                if ($charge['status'] == 'succeeded') {

                    $order->payment_status = 'done';
                    $order->payment_date = Carbon::now();
                    $order->payment_method = 1;
                    $order->billing_address = $request->billing_address;
                    $order->save();


                    foreach ($products as $product) {
                        $order->products()->attach($product, ['qty' => $product->pivot->qty]);
                        $cart->products()->detach($product);
                    }

                    Session::flash('success', 'Payment successful!');
                    return redirect('/');
                } else {
                    Session::put('error', 'Money not add in wallet!!');
                    return back();
                }
            } catch (Exception $e) {
                Session::put('error', $e->getMessage());
                return back();
            } catch (CartalystStripeExceptionCardErrorException $e) {
                Session::put('error', $e->getMessage());
                return back();
            } catch (CartalystStripeExceptionMissingParameterException $e) {
                Session::put('error', $e->getMessage());
                return back();
            }
        } else {
            $order->payment_method = 0;
            $order->payment_status = 'unpaid';
            $order->save();

            foreach ($products as $product) {
                $order->products()->attach($product, ['qty' => $product->pivot->qty]);
                $cart->products()->detach($product);
            }

            Session::flash('success', 'Order Successful!');
            return redirect('/');
        }
    }
}
