<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exceptions\Handler;
use App\Products as Products;
use Session;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Products::where('status', 1)->paginate(12);

        return view('products.list', ['products' => $products]);
    }

    public function bakla($id)
    {
        $products = Products::where('id', $id)->paginate(12);

        return view('products.edit', ['products' => $products]);
    }

    public function update(Products $product)
    {

        $product->name = request('productName');
        $product->price = request('productPrice');
        $product->qty = request('productQuantity');

        $product->save();
        Session::flash('success', 'Product updated.');
        return back();
    }

    public function delete($id)
    {
        // Delete row
        Products::where('id', $id)->delete();

        $products = Products::where('status', 1)->paginate(12);
        return view('products.list', ['products' => $products]);
    }

    /**
     * Show the details from id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function details($id)
    {
        $product = Products::findOrFail($id);

        // returns 404 if product status is not approved
        if ($product->status != 1) {
            abort(404);
        }

        return view('products.details', ['product' => $product]);
    }

    /**
     * Show the products based from result
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $name = $request->name;
        $address = $request->address;
        $product_result = null;

        if (($name == null) && ($address == null)) {
            $product_result = null;
        } else if (($name == null) && ($address != null)) {
            $product_result = Products::where('location', 'ilike', '%' . $address . '%')->paginate(12);
        } else if (($name != null) && ($address == null)) {
            $product_result = Products::where('name', 'ilike', '%' . $name . '%')->paginate(12);
        } else {
            $product_result = Products::where('name', 'ilike', '%' . $name . '%')
                ->where('location', 'ilike', '%' . $address . '%')
                ->paginate(12);
        }

        return view('products.results', [
            'product_result' => $product_result,
            'search_address' => $address,
            'search_name' => $name
        ]);
    }
}
