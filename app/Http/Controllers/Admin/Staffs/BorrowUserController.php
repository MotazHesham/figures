<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BorrowUser;
use App\Models\Borrow;
use Illuminate\Support\Facades\Cookie;

class BorrowUserController extends Controller
{
    public function store(Request $request){
        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }
        
        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:borrow_users,email',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
        ]); 

        $borrow_user = new BorrowUser;
        $borrow_user->name = $request->name;
        $borrow_user->email = $request->email;
        $borrow_user->phone = $request->phone;
        $borrow_user->save();

        flash('Success!!')->success();
        
        return redirect()->route('borrow.all');
    }

    public function edit($id){
        $borrow_user = BorrowUser::findOrFail(decrypt($id));
        return view('admin.staffs.borrow.edit_user', compact('borrow_user'));
    }

    public function update(Request $request, $id){

        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:borrow_users,email,'.$id,
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
        ]); 

        $borrow_user = BorrowUser::findOrFail($id);
        $borrow_user->name = $request->name;
        $borrow_user->email = $request->email;
        $borrow_user->phone = $request->phone;
        $borrow_user->save();

        flash('Success!!')->success();
        
        return redirect()->route('borrow.all');
    }

    public function destroy($id){
        $borrow_user = BorrowUser::findOrFail($id);
        $borrows = Borrow::where('borrow_user_id',$id)->get();
        
        if(Cookie::has('borrow')){ 
            $password = Cookie::get('borrow');
        }else{
            flash('قم بتسجيل الدخول مرة أخري لقائمة السلف')->warning();
            return redirect()->route('admin.dashboard');
        }
        foreach($borrows as $borrow){
            $borrow->delete();
        }
        $borrow_user->delete();
        flash('Success Deleted!!')->success();
        
        return redirect()->route('borrow.all');
    }
}
