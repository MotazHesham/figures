<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookiesController extends Controller
{
    public function setcookie_theme($theme){
        Cookie::queue(Cookie::make('theme',$theme,2628000));
        flash('Theme Changed To '.ucfirst($theme).' Theme Success!');
        return back();
    }

}
