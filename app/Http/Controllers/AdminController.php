<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

use App\Categories as Categories;
use App\Sellers as Sellers;
use App\Products as Products;
use App\Orders;
use Carbon\Carbon;
use App\User;

class AdminController extends Controller
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
        return view('admin.profile');
    }

    public function categoriesList()
    {
        $categories = Categories::paginate(10);

        return view('admin.categories.list', ['categories' => $categories]);
    }

    public function categoriesDetails($id)
    {
        $category = Categories::findOrFail($id);

        return view('admin.categories.details', [
            'category' => $category
        ]);
    }

    /**
     * Show the create category form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categoriesCreateForm()
    {
        return view('admin.categories.create');
    }

    /**
     * Create a new category instance.
     *
     * @param  Request  $request
     */
    public function storeCategories(Request $request)
    {

        $request->validate([
            'categoryName' => 'required',
            'categoryDescription' => 'required',
        ]);

        $category = new Categories;
        $category->name = $request->categoryName;
        $category->desc = $request->categoryDescription;

        $file = $request->file('categoryImage');
        if ($file) {
            $this->setFileUpload($file, $category);
        }

        $category->save();

        return redirect()->action('AdminController@categoriesList');
    }

    public function updateCategories(Request $request)
    {
        $request->validate([
            'categoryId' => 'required',
        ]);

        $id = (int) $request->categoryId;
        $category = Categories::find($id);

        if ($request->categoryName != null) {
            $category->name = $request->categoryName;
        }

        if ($request->categoryDescription != null) {
            $category->desc = $request->categoryDescription;
        }

        $category->save();
        return redirect()->route('adminCategoriesDetails', array('id' => $id));
    }

    public function deleteCategories($id)
    {
        $category = Categories::find($id);
        $category->delete();

        return redirect()->action('AdminController@categoriesList');
    }

    public function sellersList()
    {
        $sellers = Sellers::orderBy('id', 'asc')->paginate(10);

        return view('admin.sellers.list', ['sellers' => $sellers]);
    }

    public function sellersDetails($id)
    {
        $seller = Sellers::findOrFail($id);
        $seller->read_at = Carbon::now();
        $seller->save();
        return view('admin.sellers.details', [
            'seller' => $seller
        ]);
    }

    public function updateSellersStatus(Request $request)
    {
        $seller = Sellers::findOrFail($request->route('id'));

        $seller->status = $request->status;
        $seller->save();

        return redirect()->route('adminSellersDetails', array('id' => $seller->id));
    }

    public function productsList()
    {
        $products = Products::orderBy('id', 'desc')->paginate(10);

        return view('admin.products.list', ['products' => $products]);
    }

    public function productsDetails($id)
    {
        $product = Products::findOrFail($id);
        return view('admin.products.details', [
            'product' => $product
        ]);
    }

    public function updateProductsStatus(Request $request)
    {
        $product = Products::findOrFail($request->route('id'));

        $product->status = $request->status;
        $product->save();

        return redirect()->route('adminProductsDetails', array('id' => $product->id));
    }

    public function ordersList()
    {
        $orders = Orders::orderBy('id', 'desc')->paginate(10);

        return view('admin.orders.list', ['orders' => $orders]);
    }

    public function ordersDetails($id)
    {
        $order = Orders::find($id);
        $name = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.name'
            )
            ->where('orders.id', $id)
            ->get();
        $products = $order->products;
        $product_subtotal = 0.00;
        $product_total = [];
        $cart_items = 0;
        $product_seller_username = [];

        foreach ($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
            $cart_items += $product->pivot->qty;
            array_push(
                $product_total,
                ($product->pivot->qty * $product->price)
            );
            array_push($product_seller_username, $product->seller->user->username);
        }
        return view('admin.orders.details', [
            'name' => $name[0]->name,
            'order' => $order,
            'products' => $products,
            'product_subtotal' => $product_subtotal,
            'product_total' => $product_total,
            'cart_items' => $cart_items,
            'product_username' => $product_seller_username
        ]);
    }

    public function updateOrdersStatus(Request $request)
    {
        $id = $request->route('id');
        $order = Orders::findOrFail($id);
        $name = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.name'
            )
            ->where('orders.id', $id)
            ->get();

        $order->delivery_status = $request->status;
        $order->payment_status = 'done';
        $order->payment_date = Carbon::now();
        $order->save();

        $products = $order->products;
        $product_subtotal = 0.00;
        $product_total = [];
        $cart_items = 0;
        $product_seller_username = [];

        foreach ($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
            $cart_items += $product->pivot->qty;
            array_push(
                $product_total,
                ($product->pivot->qty * $product->price)
            );
            array_push($product_seller_username, $product->seller->user->username);
        }

        return view('admin.orders.details', [
            'name' => $name[0]->name,
            'order' => $order,
            'products' => $products,
            'product_subtotal' => $product_subtotal,
            'product_total' => $product_total,
            'cart_items' => $cart_items,
            'product_username' => $product_seller_username
        ]);
    }

    public function usersList()
    {
        $users = User::withTrashed()->orderBy('id', 'desc')->paginate(10);

        return view('admin.users.list', ['users' => $users]);
    }

    public function usersDetails($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        return view('admin.users.details', [
            'user' => $user
        ]);
    }

    public function updateUsersStatus(Request $request)
    {
        $user = User::withTrashed()->findOrFail($request->route('id'));

        if ($request->status == 1) {
            $user->delete();
        } else {
            $user->restore();
        }


        return redirect()->route('adminUsersDetails', array('id' => $user->id));
    }
}
