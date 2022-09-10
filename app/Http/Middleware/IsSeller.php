<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSeller
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
        if (Auth::check()){ 
            if( Auth::user()->user_type == 'seller') {
                return $next($request);
            } elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('home');
            }
        }else{
            return redirect()->route('user.login.form');
        }
    }
}
