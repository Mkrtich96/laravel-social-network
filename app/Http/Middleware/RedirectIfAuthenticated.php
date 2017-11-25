<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = Auth::user();

        if (Auth::guard($guard)->check()) {

            if($auth->admin){

                return redirect('/admin');
            }else{

                return redirect('/profile/' . $auth->id);
            }
        }

        return $next($request);
    }
}
