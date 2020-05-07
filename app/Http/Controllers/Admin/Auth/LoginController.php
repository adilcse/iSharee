<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm(Request $request)
    {

        return view('auth.login',['admin'=>true]);
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
        $credentials = $request->only('email', 'password');
        $this->validator($request);

        if (Auth::attempt($credentials,$request->filled('remember'))) {
            // Authentication passed...
            $user=Auth::user();
            if(1 === $user->is_admin){
                return redirect(route('admin.home'));  
            }else{
                Auth::logout();
                return redirect()->back()->with('error','admin is not active');
            }
        }
            return $this->loginFailed();
        
    }
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:admins|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules,$messages);
    }
    private function loginFailed(){
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }

    public function logout()
{
    Auth::logout();
    return redirect()
        ->route('admin.login')
        ->with('status','Admin has been logged out!');
}
}
