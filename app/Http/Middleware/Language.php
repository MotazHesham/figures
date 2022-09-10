<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;

class Language
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
        if(Session::has('locale')){
            $locale = Session::get('locale');
        }
        elseif(env('DEFAULT_LANGUAGE') != null){
            $locale = env('DEFAULT_LANGUAGE');
        }
        else{
            $locale = 'eg';
        }

        
        // if(session()->has('access_code')){ 
        //     if(session('access_code') == 'up-date'){

        //     }else{ 
        //         $request->session()->put('access_code',$request->access_code); 
        //     }
        // }else{ 
        //     $request->session()->put('access_code',$request->access_code); 
        // } 
        
        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
