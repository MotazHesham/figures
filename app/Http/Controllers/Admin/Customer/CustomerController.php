<?php

namespace App\Http\Controllers\Admin\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller_balance;
use App\Models\ReceiptCompany;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Excel;
use App\Http\Requests\StoreCustomerRequest;

class CustomerController extends Controller
{
    protected $view = 'admin.customers.';
    
    public function index(Request $request)
    {
        $users = User::with( 'orders' )->where('user_type','customer')->orderBy('created_at','desc')->get();
        return view($this->view . 'index',compact('users'));
    }

    public function create(Request $request){
        return view($this->view . 'create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated_request = $request->all();

        $validated_request['user_type'] = "customer";
        $validated_request['password'] = bcrypt($request->password);
        $customer = User::create($validated_request);
        flash(__('Customer has been inserted successfully'))->success();
        return redirect()->route('customer.index'); 
    }

    public function edit(User $customer){
        return view($this->view . 'edit',compact('customer'));
    }

    public function update(Request $request ,User $customer){
        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:users,email,'.$customer->id,
            'password'=>'nullable|min:6|confirmed',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
            'address' => 'required'
        ]); 
        $customer->email = $request->email;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        if($request->password != null){
            $customer->password = bcrypt($request->password);
        }
        if($customer->save()){ 
            flash(__('Customer has been updated successfully'))->success();
            return redirect()->route('customer.index'); 
        }

        flash(__('Something went wrong'))->error();
        return redirect()->route('customer.index'); 
    } 

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        flash(__('Success Deleted Customer'))->warning();
        return redirect()->route('customer.index'); 
    }
}
