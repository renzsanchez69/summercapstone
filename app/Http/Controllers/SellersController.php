<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Sellers as Sellers;
use App\Categories as Categories;
use App\Products as Products;
use App\Events\SellerRequestsUpdate;
use Carbon\Carbon;
use App\Images as Images;

class SellersController extends Controller
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
     * Apply as seller
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function apply($id)
    {
        $seller = Sellers::find($id);

        if (!$seller) {
            // create seller
            $sellers = new Sellers;
            $sellers->user_id = $id;
            $sellers->save();
        }

        $requests = DB::table('sellers')->whereNull('read_at')->count();
        SellerRequestsUpdate::dispatch($requests);

        return redirect()->action('UserController@index');
    }

    /**
     * Retrieve number of seller requests.
     *
     * @return json response
     */
    public function retrieve(Request $request)
    {
        try {

            // Retrieve seller requests count from "sellers table"
            $requests = DB::table('sellers')->whereNull('read_at')->count();

            // Retrieve product requests count from "sellers table"
            $pending = DB::table('products')->where('status', 0)->count();

            return response()->json([
                'success' => 'Successfully retrieved sellers requests count',
                'requests' => $requests,
                'productReqests' => $pending
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }


    public function details($id)
    {
        $seller = Sellers::findOrFail($id);
        return view('sellers.details', [
            'seller' => $seller
        ]);
    }

    public function productsList($id)
    {
        // only retrieve approved products for the specific seller
        $products = Products::where('sellers_id', $id)->where('status', 1)->paginate(12);
        $seller = Sellers::findOrFail($id);

        return view('sellers.products.list', [
            'seller' => $seller,
            'products' => $products
        ]);
    }

    /**
     * Show the create product form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function productsCreateForm()
    {
        $categories = Categories::all();
        $id = Auth::user()->id;
        $seller = Sellers::where('user_id', $id)->get()->first();

        return view('sellers.products.create', [
            'categories' => $categories,
            'id' => $seller->id,
            'location' => $seller->location
        ]);
    }

    /**
     * Create a new product instance.
     *
     * @param  Request  $request
     */
    public function storeSellersProducts(Request $request)
    {
        $request->validate([
            'productName' => 'required',
            'productPrice' => 'required|numeric',
            'productCategory' => 'required',
            'productDescription' => 'required',
        ]);

        $product = new Products;
        $product->name = $request->productName;
        $product->desc = $request->productDescription;
        $product->price = $request->productPrice;
        $product->qty = $request->qty;
        $product->location = $request->productLocation;
        $product->category_id = $request->productCategory;

        $id = Auth::user()->id;
        $seller = Sellers::where('user_id', $id)->get()->first();
        $product->sellers_id = $seller->id;

        if ($request->productLocation != null) {
            $product->location = $request->productLocation;
        } else {
            // set to seller's location if none specified
            $product->location = $seller->location;
        }

        $product->save();

        $files = $request->file('productImages');
        if ($files) {
            foreach ($files as $file) {
                $image = new Images;
                $this->setFileUpload($file, $image);
                $image->product_id = $product->id;
                $image->save();
            }
        }

        return redirect()->route('sellersProfile', [
            'id' => $seller->id,
            'success' => "true"
        ]);
    }

    /**
     * Return seller history.
     *
     * @param  Request  $request
     */
    public function history(Request $request)
    {
        $categories = Categories::all();
        $id = Auth::user()->id;
        $seller = Sellers::where('user_id', $id)->get()->first();
        $products = Products::where('id', $id)->paginate(200);

        return view('sellers.history', ['history' => $seller->history()], ['products' => $products]);
    }
}
