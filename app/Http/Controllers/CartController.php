<?php

namespace App\Http\Controllers;

use App\Categories as Categories;
use App\Products as Products;
use Auth;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->user =  Auth::user();
    }

    /**
     * Add a product to the user cart.
     *
     * @return void
     */
    public function add(Request $request) {
        try {
            $product = Products::findOrFail($request->product_id);
            $cart = Auth::user()->cart;
            $qty = (int)$request->product_qty;
            $stock = $product->qty;

            if ($qty < $product->qty) {
                if (($cart->products()->where('products_id', $product->id)->count() == 0)) {
                    $cart->products()->attach($product, ['qty' => $qty]);
                    $product->update(['qty' => ($product->qty - $qty)]);
                    $product_count = $cart->products()->count();

                    return response()->json(['success'=>'Data is successfully added']);
                } else {
                    $product_count = $cart->products()->count();
                    return response()->json(['success'=>'Data is already in cart']);
                }
            } else {
                return response()->json(['error'=>'Stock not enough!',
                                         'stocks'=>$stock]);
            }
        } catch(Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 404);
        }
    }

    /**
     * Count products to the user cart.
     *
     * @return product count
     */
    public function count(Request $request) {
        try {
            $cart = \Auth::user()->cart;
            $product_count = $cart->products()->count();
            return response()->json(['success'=>'Successfully retrieved product count',
                'product_count' => $product_count]);
        } catch(Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 404);
        }
    }

    /**
     * Retrieve products in html form to cart dropdown.
     *
     * @return products html
     */
    public function retrieve(Request $request) {
        try {
            if (Auth::user()->isAdmin()) {
                return response()->json(['success'=>'Successfully retrieved product count',
                'product_html' => '',
                'product_count' => 0,
                'product_subtotal' => 0]);
            }
            $cart = Auth::user()->cart;
            $products = $cart->products;
            $product_html = '';
            $product_count = $cart->products()->count();
            $product_subtotal = 0.00;
            foreach($products as $product) {
                $product_html .= '<div class="product-widget" id="cart-product-'. $product->id.'">';
                $source = '/img/blank.png';
                $alt = "blank";

                if (!$product->images->isEmpty()) {
                    // get first image
                    $source = '/uploads/'.$product->images[0]->filename;
                    $alt = $product->name;
                }

                $product_html .= '<div class="product-img">';
                $product_html .= '<img ';
                $product_html .= 'src="'.$source.'"';
                $product_html .= 'alt="'.$alt.'">';
                $product_html .= '</div>';

                $product_html .= '<div class="product-body">';

                $product_html .= '<h3 class="product-name">';
                $product_html .= '<a href="/products/'.$product->id.'">';
                $product_html .= $product->name;
                $product_html .= '</a></h3>';

                $product_html .= '<h4 class="product-price">';
                $product_html .= '<span class="qty">';
                $product_html .= ''.$product->pivot->qty.'x';
                $product_html .= '</span>';
                $product_html .= 'K '.$product->price;
                $product_html .= '</h4>';

                $product_html .='</div>';

                $product_html .= '<button class="delete" onclick="delete_cart_item('.$product->id.')"><i class="fa fa-close"></i></button>';

                $product_html .='</div>';
                $product_subtotal += $product->pivot->qty * $product->price;
            }


            return response()->json(['success'=>'Successfully retrieved product count',
                'product_html' => $product_html,
                'product_count' => $product_count,
                'product_subtotal' => $product_subtotal]);

        } catch(Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 404);
        }
    }


    /**
     * Remove a product to the user cart.
     *
     * @return void
     */
    public function delete(Request $request) {
        try {
            $product = Products::findOrFail($request->product_id);
            $cart = Auth::user()->cart;

            if ($cart->products()->where('products_id', $product->id)->count() > 0) {
                $qty = $cart->products()->find($product->id)->pivot->qty;
                $product->update(['qty' => ($product->qty + $qty)]);
                $cart->products()->detach($product);
                $products = $cart->products;
                $product_subtotal = 0.0;
                $cart_items = 0;

                foreach($products as $product) {
                    $product_subtotal += $product->pivot->qty * $product->price;
                    $cart_items += $product->pivot->qty;
                }

                return response()->json(['success'=>'Data is successfully removed',
                                         'product_subtotal' => $product_subtotal,
                                         'cart_items' => $cart_items]);
            } else {
                return response()->json(['success'=>'Data is not in the cart']);
            }

        } catch(Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 404);
        }
    }

    /**
     * Return cart view.
     *
     * @return void
     */
    public function index(Request $request) {
        $cart = Auth::user()->cart;
        $products = $cart->products;
        $product_subtotal = 0.0;
        $product_total = [];
        $cart_items = 0;
        $address = Auth::user()->address;

        foreach($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
            $cart_items += $product->pivot->qty;
            array_push($product_total,
                ($product->pivot->qty * $product->price));
        }

        return view('cart.index', [
            'products' => $products,
            'product_subtotal' => $product_subtotal,
            'product_total' => $product_total,
            'cart_items' => $cart_items,
            'address' => $address
        ]);
    }


    /**
     * Update a product to the user cart.
     *
     * @return void
     */
    public function update(Request $request) {
        try {
            $product = Products::findOrFail($request->product_id);
            $cart = Auth::user()->cart;
            $qty = (int)$request->product_qty;
            $current_qty = $cart->products()->find($product->id)->pivot->qty;
            $stock = $product->qty;
            $update_product_qty = 0;

            if ($qty > $current_qty) {
                $update_product_qty = $stock - ($qty - $current_qty);
            } else {
                $update_product_qty = $stock + ($current_qty - $qty);
            }

            if ($update_product_qty > 0) {
                if ($cart->products()->where('products_id', $product->id)->count() != 0) {
                    $cart->products()->updateExistingPivot($product, ['qty' => $qty]);
                    $product->update(['qty' => ($update_product_qty)]);
                    $product_count = $cart->products()->count();
                    $products = $cart->products;
                    $product_subtotal = 0.0;
                    $cart_items = 0;

                    foreach($products as $product) {
                        $product_subtotal += $product->pivot->qty * $product->price;
                        $cart_items += $product->pivot->qty;
                    }

                    return response()->json(['success'=>'Data is successfully updated',
                                             'product_subtotal' => $product_subtotal,
                                             'product_total' => ($qty * $product->price),
                                             'qty' => $qty,
                                             'update_product_qty' => $update_product_qty,
                                             'current_qty' => $current_qty,
                                             'stock' => $stock,
                                             'cart_items' => $cart_items]);
                } else {
                    $product_count = $cart->products()->count();
                    return response()->json(['success'=>'Data is not in the cart']);
                }
            } else {
                $qty = $cart->products()->find($product->id)->pivot->qty;
                return response()->json(['error'=>'Stock not enough!',
                                         'current_qty'=>$current_qty], 404);
            }
        } catch(Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 404);
        }
    }

    /**
     * Retrieve product qty from the user cart.
     *
     * @return void
     */
    public function get_qty(Request $request) {
        try {
            $product = Products::findOrFail($request->product_id);
            $cart = Auth::user()->cart;
            $qty = $cart->products()->find($product->id)->pivot->qty;

            return response()->json(['success'=>'Data is successfully updated',
                                     'qty' => $qty]);

        } catch(Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 404);
        }
    }

}
