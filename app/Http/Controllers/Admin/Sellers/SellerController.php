<?php

namespace App\Http\Controllers\Admin\Sellers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Seller_product;
use App\Models\BusinessSetting;
use App\Models\OrderDetail;
use App\Models\GeneralSetting;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreSellerRequest;

class SellerController extends Controller
{

    protected $view = 'admin.sellers.sellers.';

    public function send_welcome_message($user_id,$email,$seller_code){
        $generalsetting = GeneralSetting::first();

        $conversation = new Conversation;
        $conversation->receiver_id = $user_id;
        $conversation->sender_id = User::where('user_type','admin')->first()->id;
        $conversation->title = $email;
        $conversation->save();
        
        $message = new Message;
        $message->conversation_id = $conversation->id;
        $message->user_id = User::where('user_type','admin')->first()->id;
        $message->message = $generalsetting->welcome_message;
        $message->save();

        //send seller code
        $message = new Message;
        $message->conversation_id = $conversation->id;
        $message->user_id = User::where('user_type','admin')->first()->id;
        $message->message = 'This is your code ' . $seller_code ;
        $message->save();
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ=#%$@&';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $users = User::with(['seller','orders'])->where('user_type', 'seller')->orderBy('created_at', 'desc')->get();
        return view($this->view . 'index', compact('users' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view . 'create');
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellerRequest $request)
    {
        $validated_request = $request->all();

        $validated_request['user_type'] = "seller";
        $validated_request['password'] = bcrypt($request->password);
        $user = User::create($validated_request);

        $seller = new Seller;
        $seller->social_name = $request->social_name;
        $seller->social_link = $request->social_link;
        $seller->seller_type = $request->seller_type;
        $seller->discount_code = $request->discount_code;
        $seller->discount = $request->discount;
        $seller->verification_status = 1;
        $seller->qualification = $request->qualification;

        if($user->save()){

            $random_string = $this->generateRandomString();
            $seller->user_id = $user->id;
            $seller->seller_code = $user->id . $random_string;  
            $this->send_welcome_message($user->id , $user->email,$seller->seller_code);

            if($request->hasFile('identity_front')){
                $seller->identity_front = $request->identity_front->store('uploads/seller/'.$user->id.'/identity');
            }
            if($request->hasFile('identity_back')){
                $seller->identity_back = $request->identity_back->store('uploads/seller/'.$user->id.'/identity');
            }

            if($seller->save()){
                flash(__('Seller has been inserted successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(__('Something went wrong'))->error();
        return back();
    } 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('seller')->findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); 
        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:users,email,'.$id,
            'password'=>'nullable|min:6|confirmed',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
            'address' => 'required',
            'discount_code' => 'nullable|min:3|max:8|unique:sellers,discount_code,'.$user->seller->id,
        ]); 
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }

        if($user->seller){
            $seller = $user->seller;
        }else{
            $seller = new Seller;
            $seller->user_id = $user->id;
        }

        $seller->social_name = $request->social_name;
        $seller->social_link = $request->social_link;
        $seller->qualification = $request->qualification;
        $seller->order_out_website = $request->order_out_website;
        $seller->seller_type = $request->seller_type;
        $seller->discount_code = $request->discount_code;
        $seller->discount = $request->discount;
        if($request->hasFile('identity_front')){
            Storage::delete($seller->identity_front);
            $seller->identity_front = $request->identity_front->store('uploads/seller/'.$user->id.'/identity');
        }
        if($request->hasFile('identity_back')){
            Storage::delete($seller->identity_back);
            $seller->identity_back = $request->identity_back->store('uploads/seller/'.$user->id.'/identity');
        }
        
        if($user->save()){
            if($seller->save()){
                flash(__('Seller has been updated successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(__('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::with('seller')->findOrFail($id);
        if($user->seller){
            $user->seller->delete();
        }
        if($user->delete()){ 
            flash(__('Seller has been deleted successfully'))->success();
            return redirect()->route('sellers.index'); 
        }
        else {
            flash(__('Something went wrong'))->error();
            return back();
        }
    } 


    public function updateApproved(Request $request)
    {
        $user = User::findOrFail($request->id);
        $seller = Seller::where('user_id',$user->id)->first();
        $seller->verification_status = $request->status;
        if($seller->save()){
            return 1;
        }
        return 0;
    }
}
