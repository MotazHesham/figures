<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckRole
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
        if(Auth::check()){
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff' )) {
                return redirect()->route('admin.dashboard'); 
            }elseif ( Auth::user()->user_type == 'delivery_man') {
                return redirect()->route('deliveryman.orders.index','on_delivery');
            }elseif ( Auth::user()->user_type == 'seller') {
                return $next($request);
            }elseif ( Auth::user()->user_type == 'desginer') {
                return $next($request);
            }
        }else{
            return redirect()->route('home');
        }
    }
}
