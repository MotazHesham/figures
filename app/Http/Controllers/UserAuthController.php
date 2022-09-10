<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Seller;
use App\Models\GeneralSetting;
use App\Models\UserAlert; 
use App\Http\Controllers\PushNotificationController;
use Hash;
use Illuminate\Support\Facades\Storage;


class UserAuthController extends Controller
{
    
    public function show_profile(){
        return view('frontend.profile');

    }

    public function update_password(Request $request){
        
        $this->validate($request,[
            'password'=>'required|min:6|confirmed',
        ]); 

        $user = Auth::user();  
        $hashedPassword = $user->password;
        if(!\Hash::check($request->old_password, $hashedPassword) && $user->password != null){
            flash('Current Password Not Correct')->error();
            return redirect()->route('profile');
        }else{
            $user->password = bcrypt($request->password);
            $user->save();
            flash('Success Changed Password')->success();
            return redirect()->route('profile');
        } 
    }
    
    // ----------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------

    public function update_profile(Request $request)
    {

        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone = $request->phone_number;
        
        if($request->hasFile('photo')){
            Storage::delete($user->avatar_original);
            $user->avatar_original = $request->photo->store('uploads/seller/'.$user->id.'/avatar');
        }

        if($user->user_type == 'designer'){
            $this->validate($request,[
                'store_name'=>'required|min:3|max:50|unique:users,store_name,'.$user->id,
            ]); 
            $user->store_name = str_replace(' ', '-', $request->store_name);
        }

        if($user->user_type == 'seller'){
            if($user->seller){
                $seller = $user->seller;
            }else{
                $seller = new Seller;
                $seller->user_id = $user->id;
            }
            $seller->social_name = $request->social_name;
            $seller->social_link = $request->social_link;
            $seller->qualification = $request->qualification;
            if($request->hasFile('identity_front')){
                Storage::delete($seller->identity_front);
                $seller->identity_front = $request->identity_front->store('uploads/seller/'.$user->id.'/identity');
            }
            if($request->hasFile('identity_back')){
                Storage::delete($seller->identity_back);
                $seller->identity_back = $request->identity_back->store('uploads/seller/'.$user->id.'/identity');
            }
            $seller->save();
        }


        if($user->save()){ 
            flash(__('Your Profile has been updated successfully!'))->success();
            return back(); 
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }

    // ----------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------

    public function register_form() {
        if(Auth::check()){
            return back();
        }
        else{
            return view('frontend.auth.register');
        }
    }

    

    // ----------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------

    public function register(Request $request){

        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:users,email',
            'store_name'=>'nullable|min:3|max:50|unique:users,store_name',
            'password'=>'required|min:6|confirmed',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
            'address' => 'nullable'
        ]); 

        if(!Auth::check()){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            if($request->type == 's'){
                $user->user_type = "seller";
                $title = $user->name;
                $body = 'طلب أنضمام جديد من بائع';
                $route = route('sellers.index');
            }elseif($request->type == 'd'){
                $user->user_type = "designer";
                $user->store_name = str_replace(' ', '-', $request->store_name);
                $title = $user->name;
                $body = 'تسجيل حساب جديد من مصمم';
                $route = route('designers.index');
            }else{
                $user->user_type = "customer";
                $title = $user->name;
                $body = 'تسجيل حساب جديد من زبون';
                $route = route('customer.index');
            }
            $user->password = Hash::make($request->password);

            

            if($user->save()){
                if($request->type == 's'){
                    $random_string = $this->generateRandomString();
                    $seller = new Seller;
                    $seller->social_name = $request->social_name;
                    $seller->social_link = $request->social_link;
                    $seller->verification_status = 0;
                    $seller->qualification = $request->qualification;
                    $seller->user_id = $user->id;
                    $seller->seller_code = $user->id . $random_string;  
                    $seller->save();
                    $this->send_welcome_message($user->id , $user->email,$seller->seller_code);
                }else{ 
                    $generalsetting = GeneralSetting::first();
            
                    $conversation = new Conversation;
                    $conversation->receiver_id = $user->id;
                    $conversation->sender_id = User::where('user_type','admin')->first()->id;
                    $conversation->title = $user->email;
                    $conversation->save();
                    
                    $message = new Message;
                    $message->conversation_id = $conversation->id;
                    $message->user_id = User::where('user_type','admin')->first()->id;
                    $message->message = $generalsetting->welcome_message;
                    $message->save();
                }
                auth()->login($user, true);
                
                UserAlert::create([
                    'alert_text' => $title . ' ' . $body,
                    'alert_link' => $route,
                    'type' => 'register',
                    'user_id' => 0 ,
                ]);  

                $tokens = User::whereNotNull('device_token')->whereIn('user_type',['staff','admin'])->where(function ($query) {
                                                                $query->where('notification_show',1)
                                                                        ->orWhere('user_type','admin');
                                                            })->pluck('device_token')->all(); 
                $push_controller = new PushNotificationController();
                $push_controller->sendNotification($title, $body, $tokens,$route); 

                flash(__('Your Account has been created successfully!'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(__('something wrong!'))->error();
        return back();
    }

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

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ=#%$@&';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // ----------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------

    public function login_form()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return view('frontend.auth.login');
    }

    // ----------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------

    public function login(Request $request){
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->remember)) {

            if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff'){
                return redirect()->route('admin.dashboard');
            }
            else{
                return redirect()->route('dashboard');
            }
        }
        
        flash(__('Invalid email or password'))->error();
        return back()->withInput($request->only('email'));
    }

}
