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
                Auth::logout();
                return view('auth.login',['error'=>'please login through admin pannel']);
            }
            if($user->is_email_verified && $user->is_active){
                return redirect(route('home'));  
            }else if(!$user->is_active){
                Auth::logout();
                return view('auth.login',['error'=>'user, your account is blocked.']);
            }
            else{
                $email=urlencode($user->email);
                Auth::logout();
                return  redirect('/email/verify?email='.$email);
            }
          //  return redirect()->intended('dashboard');
        }else{
            return view('auth.login',['error'=>'email/password']);
        }
        
    }
    public function showLoginForm(Request $request)
    {
        if($request->input('verify')==='success'){
            $email=urldecode($request->input('email'));
            return view('auth.login',['email'=>$email,'verify'=>true]);
        }
        return view('auth.login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
    public function GuestLogin(Request $request)
    {
        $email='guest@mail.com';
        $password='iamguest';
        if(Auth::attempt(['email' => $email, 'password' => $password])){

            return redirect(route('home'));  
        }
        return redirect(route('login'));
    }
}
