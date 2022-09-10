<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Socialite;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Check user's role and redirect user based on their role
     * @return
     */

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        Session::put('link', url()->previous());
        return Socialite::driver($provider)->redirect();
    }


    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    { 
        try {
            if ($provider == 'twitter') {
                $user = Socialite::driver('twitter')->user();
            } else {
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            flash("Something Went wrong. Please try again.")->error();
            return redirect()->route('user.login.form');
        } 

        // check if they're an existing user
        $existingUser = User::where('provider_id', $user->id)->orWhere('email', $user->email)->first();

        if ($existingUser) {
            // log them in
            if($existingUser->user_type == 'customer'){
                auth()->login($existingUser, true);
            }else{
                flash(__('This Account Exisit As ' . $existingUser->user_type . ' Account Use Diffrent Account To Register'))->error();
                return redirect()->route('user.login.form');
            }
        } else {
            // create a new user
            $newUser = new User;
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->email_verified_at = date('Y-m-d H:m:s');
            $newUser->provider_id = $user->id;
            $newUser->user_type = 'customer';

            // $extension = pathinfo($user->avatar_original, PATHINFO_EXTENSION);
            // $filename = 'uploads/users/'.str_random(5).'-'.$user->id.'.'.$extension;
            // $fullpath = 'public/'.$filename;
            // $file = file_get_contents($user->avatar_original);
            // file_put_contents($fullpath, $file);
            //
            // $newUser->avatar_original = $filename;
            $newUser->save(); 

            auth()->login($newUser, true);
        }
        if (Session::get('link') != null) {
            return redirect(Session::get('link'));
        } else {
            return redirect()->route('home');
        }
    }


    public function authenticated()
    {
        if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff'){
            return redirect()->route('admin.dashboard');
        }elseif(auth()->user()->user_type == 'delivery_man'){
            return redirect()->route('deliveryman.orders.index','on_delivery');
        }else{
            return redirect()->route('profile');
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(__('Invalid email or password'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if(auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff' || auth()->user()->user_type == 'delivery_man')){
            $redirect_route = 'login';
        }
        else{
            $redirect_route = 'home';
        }

        $this->guard()->logout();

        $request->session()->invalidate();
        if(Cookie::has('device_token')){
            $cookie = Cookie::forget('device_token');
            return $this->loggedOut($request) ?: redirect()->route($redirect_route)->withCookie($cookie);
        }else{
            return $this->loggedOut($request) ?: redirect()->route($redirect_route);
        }
    }
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
