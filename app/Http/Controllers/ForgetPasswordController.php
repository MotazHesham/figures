<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Config;
use Carbon\Carbon;
use App\Traits\send_mail_trait;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    use send_mail_trait;

    public function create_token(Request $request) {
        $this->validate($request,[
            'email' => 'required|email|max:255',
        ]);
        

        $user = User::where('email', $request->email)->first();

        if (!$user){
            flash(__('Sorry! email not found.'))->error();
            return back();
        }    

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        if ($user && $passwordReset){
            $url = 'https://ebtekarstore.net/forgetpassword/'.$passwordReset->token;
            $this->sendEmail( "Reset Password Link " . $url , $request->email,"Password Reset From EbtekarStore.net");
        }

        flash(__('Reset password link sent on your email'))->success();
        return back();
    }

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
            
        if (!$passwordReset){
            flash(__('Sorry! Token Expired'))->error();
            return back();
        }
            
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            flash(__('Sorry! Token Expired'))->error();
            return back();
        }
        return view('auth.passwords.reset',compact('token'));
    }

    public function reset(Request $request) {
        $this->validate($request,[ 
            'token' => 'required|string',
            'password' => 'required|min:6|max:20|confirmed'
        ]);

        $passwordReset = PasswordReset::where('token',  $request->token)->first();

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user){
            flash(__('Sorry! email not found.'))->error();
            return back();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        flash(__('Password Changed Successfully'))->error();
        return back();
    }
}
