<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * admin login controller handles admin login actions
 */
class LoginController extends Controller
{
    /**
     * if user is not already logged in then only 
     * he/she can view login page
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * when user click admin login page then it returns a login page for admin
     */
    public function showLoginForm()
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
        //validate user's input
        $this->validator($request);
        //authenticate user with credentials
        if (Auth::attempt($credentials,$request->filled('remember'))) {
            // Authentication passed...
            $user=Auth::user();
            //allow login if user is admin
            if(1 === $user->is_admin){
                return redirect(route('admin.home'));  
            }
        }
        //logout when credential fails
            return $this->loginFailed();
        
    }
    /**
     * validate user's input request
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:admins|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => 'Invalid password',
        ];

        //validate the request.
        $request->validate($rules,$messages);
    }

    /**
     * if login fails then return to previous page with a message
     */
    private function loginFailed(){
        Auth::logout();
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }

    /**
     * logs out the user from admin pannel
     */
    public function logout()
{
    Auth::logout();
    return redirect()
        ->route('admin.login')
        ->with('status','Admin has been logged out!');
}
}
