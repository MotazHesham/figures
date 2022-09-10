<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\BorrowUser;
use App\Models\Subtract;
use App\Models\GeneralSetting;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class BorrowController extends Controller
{
    protected $view = 'admin.staffs.borrow.';

    public function status($id){
        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }

        $borrow = Borrow::findOrFail(decrypt($id));
        $borrow->status = 1;
        $borrow->save();
        flash('Success!!')->success();
        return redirect()->route('borrow.all');
    }

    public function status_all(Request $request){
        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }

        if($request->borrows){
            $borrows = Borrow::whereIn('id',$request->borrows)->get();
            if($borrows){
                foreach($borrows as $borrow){
                    $borrow->status = 1;
                    $borrow->save();
                }
                flash('Success!!')->success();
            }
        }
        return redirect()->route('borrow.all');
    }

    public function all(Request $request){
        $general_setting = GeneralSetting::first(); 
        $borrow_user = BorrowUser::with('borrow')->orderBy('created_at','desc')->get();
        $borrow = Borrow::with('borrow_user')->where('status',0)->orderBy('status','asc')->orderBy('created_at','desc')->get();
        $subtracts = Subtract::with('subtract_user')->orderBy('created_at','desc')->get();

        $password = $request->password;
        if($general_setting->borrow_password == $password){ 
            
            Cookie::queue(Cookie::make('borrow',$request->password,20));
            return view($this->view . 'index',compact('borrow','borrow_user','password','subtracts'));
        }elseif(Cookie::has('borrow') && $general_setting->borrow_password == Cookie::get('borrow')){
            return view($this->view . 'index',compact('borrow','borrow_user','password','subtracts'));
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }
    }
    

    public function store(Request $request){
        
        $this->validate($request,[
            'borrow_user_id'=>'required|integer',
            'amount' => 'required'
        ]); 

        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }

        $borrow = new Borrow; 
        $borrow->borrow_user_id = $request->borrow_user_id;
        $borrow->amount = $request->amount;
        $borrow->save();

        flash('Success!!')->success();
        return redirect()->route('borrow.all');
    }

    public function edit($id){
        $borrow = Borrow::findOrFail(decrypt($id));
        return view($this->view . 'edit',compact('borrow'));
    }

    public function update(Request $request,$id){

        $this->validate($request,[
            'amount' => 'required'
        ]); 

        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }

        $borrow = Borrow::findOrFail($id);
        $borrow->amount = $request->amount;
        $borrow->save();

        flash('Success!!')->success();
        return redirect()->route('borrow.all');
    }

    public function destroy($id)
    {
        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }
        
        Borrow::destroy($id);
        flash(__('Deleted successfully'))->success();
        return redirect()->route('borrow.all');
    }

}
