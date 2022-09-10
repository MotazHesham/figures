<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PlaylistPermission
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
        if( Auth::user()->user_type == 'admin' ||
            in_array('20', json_decode(Auth::user()->staff->role->permissions)) ||
            in_array('21', json_decode(Auth::user()->staff->role->permissions)) ||
            in_array('22', json_decode(Auth::user()->staff->role->permissions)) ){
            return $next($request);
        }else{
            return abort(403);
        }
    }
}
