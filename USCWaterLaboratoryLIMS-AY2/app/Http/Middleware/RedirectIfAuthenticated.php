<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            //Redirect Users according to User Type
            $type = Auth::user()->userType;
        
            //Checking type..
            switch ($type) {
                case 'administrator':
                    return redirect('/admin/dashboard');
                    break;
                case 'secretary':
                    return redirect('/secretary/home');
                    break;
                case 'analyst':
                    return redirect('/analyst/notification');
                    break;
                default:
                    return '/home';
                    break;
            }

            return redirect('/home');
        }

        return $next($request);
    }
}
