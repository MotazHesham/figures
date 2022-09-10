<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subtract;
use App\Models\BorrowUser;
use App\Models\GeneralSetting;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class SubtractController extends Controller
{
    protected $view = 'admin.staffs.subtract.';   

    public function store(Request $request){
        
        $this->validate($request,[
            'subtract_user_id'=>'required|integer',
            'amount' => 'required'
        ]); 

        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }

        $subtract = new Subtract; 
        $subtract->subtract_user_id = $request->subtract_user_id;
        $subtract->amount = $request->amount;
        $subtract->reason = $request->reason;
        $subtract->save();

        flash('Success!!')->success();
        return redirect()->route('borrow.all');
    }

    public function edit($id){
        $subtract = Subtract::findOrFail(decrypt($id));
        return view($this->view . 'edit',compact('subtract'));
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

        $subtract = Subtract::findOrFail($id);
        $subtract->amount = $request->amount;
        $subtract->reason = $request->reason;
        $subtract->save();

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
        
        Subtract::destroy($id);
        flash(__('Deleted successfully'))->success();
        return redirect()->route('borrow.all');
    }

}
