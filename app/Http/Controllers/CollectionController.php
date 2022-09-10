<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Design;
use App\Models\Category;
use App\Models\Mockup;
use App\Models\Collection;
use App\Models\CollectionImage;

class CollectionController extends Controller
{
    public function index(){
        $categories = Category::where('design',1)->paginate(9);
        return view('frontend.collections.collections',compact('categories'));
    }

    public function mockups($id){
        $path = 'public/uploads/designers/' . Auth::user()->store_name;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            mkdir($path.'/my-designes', 0777, true);
            mkdir($path.'/collections', 0777, true);
        }
        $mockups = Mockup::where('category_id',$id)->paginate(9); 
        if(count($mockups) > 0){
            return view('frontend.collections.mockups',compact('mockups'));
        }else{
            flash(__('No Mockups Found'))->warning();
            return back();
        }
    } 

    public function start($id){
        $designs = Design::where('user_id',Auth::id())->get();
        $mockup = Mockup::findOrFail($id); 
        return view('frontend.collections.start',compact('designs','mockup'));
    } 


    

}
