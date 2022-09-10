<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Design;

class DesignController extends Controller
{
    public function index(){
        $designs = Design::where('user_id',Auth::id())->paginate(10);
        return view('frontend.designs.index',compact('designs'));
    }

    public function store(Request $request){
        $user = Auth::user();

        $design = new Design;
        $design->design = $request->design->store('uploads/designers/'.$user->store_name.'/my-designes');
        $design->user_id = $user->id;
        $design->save();
        
        flash(__('Uploaded Successfully'))->success();
        return redirect()->route('design.index');
    }

    public function destroy($id){
        $design = Design::findOrFail($id);
        $design->delete();
        flash(__('Deleted Successfully!'))->success();
        return redirect()->route('design.index');
    }
}
