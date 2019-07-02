<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Sellers as Sellers;

class IsApprovedSeller
{

    /**
     * Handle an incoming request.
     * Checks if current user is an approved seller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $seller = Sellers::where('user_id', Auth::user()->id)->get()->first();

        if($seller->status == 1) {
            return $next($request);
        }

        return redirect('home');
    }

}
