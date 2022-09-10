<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Auth;

class FavoriteController extends Controller
{
    public function favorites(){
        
        $favorites = Favorite::with('product')->where('user_id',Auth::user()->id)->paginate(3);

        return view('frontend.favorites', compact('favorites'));
    }

    public function addtofavorites(Request $request){
        $favorite = Favorite::all()->where('user_id',Auth::user()->id)->where('product_id',$request->id);
        if($favorite->count() > 0){
            return back()->with('error','This Product already in your favorites');
        }
        $favorite = new Favorite;
        $favorite->user_id = Auth::user()->id;
        $favorite->product_id = $request->id;
        $favorite->save();
        return back()->with('success','Product added Successfully to your favorites');
    }
}
