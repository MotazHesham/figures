<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PriceViewReceiptPermission
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
        if( Auth::user()->user_type == 'admin' || in_array('23', json_decode(Auth::user()->staff->role->permissions)) ){
            return $next($request);
        }else{
            return abort(403);
        }
    }
}
