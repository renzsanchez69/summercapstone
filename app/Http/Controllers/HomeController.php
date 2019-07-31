<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type = Auth::user()->userType;
        //Checking type..
        switch ($type) {
            case 'administrator':
                return redirect('/admin/home');
                break;
            case 'secretary':
                return redirect('/secretary');
                break;
            case 'analyst':
                return redirect('/analyst');
                break;
            default:
                return redirect('/home');
                break;
        }
    }
}
