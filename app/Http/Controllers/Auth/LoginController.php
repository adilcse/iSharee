<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = ['email'=>$request->input('email'), 'password'=>$request->input('password')];
        if (Auth::attempt($credentials,$request->filled('remember'))) {
            // Authentication passed...
            $user=Auth::user();
            if(1 === $user->is_admin){
                return redirect(route('admin.home'));
            }
            else if(0===$user->is_active){
                Auth::logout();
                return view('auth.login',['error'=>'user, your account is blocked.']);
            }
            else if(0===$user->is_email_verified){
                $email=urlencode($user->email);
                Auth::logout();
                return  redirect('/email/verify?email='.$email);
            }
            // twilio mobile verification suspended will add after account resumes
            // else if(0===$user->is_mobile_verified){
            //     Auth::logout();
            //     $mobile=urlencode($user->mobile);
            //     return  redirect('/mobile/verify?number='.$mobile.'&resend=true');
            // }
            return redirect(route('home')); 
        }else{
            return view('auth.login',['error'=>'email/password']);
        }
        
    }

    /**
     * show form for user login
     */
    public function showLoginForm(Request $request)
    {
        //redirected user fill email field for login
        if($request->input('verify')==='success'){
            $email=urldecode($request->input('email'));
            return view('auth.login',['email'=>$email,'verify'=>true]);
        }
        return view('auth.login');
    }

    /**
     * logout user and redirect to login page
     */
    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }

    /**
     * guest user can view home page by logging in as guest
     */
    public function GuestLogin(Request $request)
    {
        if(Auth::loginUsingId(0)){
            return redirect(route('home'));  
        }
        return redirect(route('login'));
    }
}
