<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserAlert;
use Auth;

class UserALertsController extends Controller
{
    
    public function notification_seen(Request $request){
        $user_alert = UserAlert::find($request->id);
        $user_alert->seen = 1;
        $user_alert->save();
        return 1;
    }

    public function view_history(){
        $alerts = UserAlert::orderBy('created_at','desc')->where(function ($query) {
            $query->where('type','history')
                    ->where('user_id',Auth::user()->id);
        })->get();
        
        return view('admin.alerts',compact('alerts'));
    }

    public function view_all(){
        $alerts = UserAlert::orderBy('created_at','desc')->where(function ($query) {
            $query->where('type','private')
                    ->where('user_id',Auth::user()->id);
        }); 
        if(Auth::user()->user_type == 'staff' && Auth::user()->notification_show == 1){
            $alerts = $alerts->orWhereIn('type',['playlist','orders','register','designs','commission']); 
        }elseif(Auth::user()->user_type == 'admin'){
            $alerts = $alerts->orWhereIn('type',['playlist','orders','register','designs','commission']); 
        }  

        $alerts = $alerts->get();
        
        return view('admin.alerts',compact('alerts'));
    }

}
