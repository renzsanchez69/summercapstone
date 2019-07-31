<?php

namespace App\Http\Middleware;

use Closure;

class secretaryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->userType != 'secretary') {
            return redirect('/home');
        }
        return $next($request);
    }
}
