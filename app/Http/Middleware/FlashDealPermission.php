<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FlashDealPermission
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
        if( Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)) ){
            return $next($request);
        }else{
            return abort(403);
        }
    }
}
