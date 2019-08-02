<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Products as Products;
use App\Categories;
use App\Events\SellerRequestsUpdate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // SellerRequestsUpdate::dispatch(1);
        $products = Products::where('status', 1)->paginate(12);
        return view('home', ['products' => $products]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contactUs()
    {
        return view('contact');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function aboutUs()
    {
        return view('about');
    }
}
