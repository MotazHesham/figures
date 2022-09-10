<?php

namespace App\Http\Controllers\Admin\DeliveryMan;

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
use App\Http\Requests\StoreDeliveryManRequest;

class DeliveryManController extends Controller
{
    protected $view = 'admin.delivery_man_list.';

    public function dashboard(){
        return view('delivery_man.dashboard');
    } 

    public function index(Request $request)
    {
        $users = User::where('user_type','delivery_man')->orderBy('created_at','desc')->get();
        return view($this->view . 'index',compact('users'));
    }

    public function create(Request $request){
        return view($this->view . 'create');
    }

    public function store(StoreDeliveryManRequest $request)
    {
        $validated_request = $request->all();

        $validated_request['user_type'] = "delivery_man";
        $validated_request['password'] = bcrypt($request->password);
        $deliveryman = User::create($validated_request);
        flash(__('DeliveryMan has been inserted successfully'))->success();
        return redirect()->route('deliveryman.index'); 
    }

    public function edit(User $deliveryman){
        return view($this->view . 'edit',compact('deliveryman'));
    }

    public function update(Request $request ,User $deliveryman){
        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:users,email,'.$deliveryman->id,
            'password'=>'nullable|min:6|confirmed',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
            'address' => 'required'
        ]); 
        $deliveryman->email = $request->email;
        $deliveryman->name = $request->name;
        $deliveryman->address = $request->address;
        $deliveryman->phone = $request->phone;
        if($request->password != null){
            $deliveryman->password = bcrypt($request->password);
        }
        if($deliveryman->save()){ 
            flash(__('DeliveryMan has been updated successfully'))->success();
            return redirect()->route('deliveryman.index'); 
        }

        flash(__('Something went wrong'))->error();
        return redirect()->route('deliveryman.index'); 
    } 

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        flash(__('Success Deleted DeliveryMan'))->warning();
        return redirect()->route('deliveryman.index'); 
    }



}
