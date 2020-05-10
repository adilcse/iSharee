<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\Otp;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    public function showLinkRequestForm(Request $request)
    {
        return view('auth.passwords.reset');
    }
    /**
     * send otp for verification
     */
    public function send(Request $request)
    {
        $email=$request->input('email');
        $user=User::where('email',$email)->first();
        if($user){
            $otp=Otp::emailOtp($email);
            if($user->is_email_verified === 1 && $otp){
                Mail::to($email)->send(new EmailVerify($otp));
                return json_encode(['data'=>'otp sent success','success'=>true]);
            }
            else{
                return json_encode(['data'=>'email not verified','success'=>false]);
            }
        }
        else{
            return json_encode(['data'=>'user not exist','success'=>false]);
        }
    }

    /**
     * verifies user input otp and reset password if otp verifies
     * @param request object
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email'=>'email|required',
            'password'=>'string|required|min:6',
            'otp'=>'numeric|required|digits:4'
        ]);
        $email=$request->input('email');
        $password=$request->input('password');
        $otp=$request->input('otp');
        //verify otp from already generaed table
        if(Otp::verifyEmailOtp($email,$otp)){
            $data=User::where('email',$email)->update(['password'=>Hash::make($password)]);
            if(1 === $data){
                return json_encode(['data'=>'password set successfull','success'=>true]);
            }else{
                return json_encode(['data'=>'password not set','success'=>false]);
            }
        }
        else{
                return json_encode(['data'=>'invalid otp','success'=>false]);
        }     
    }
}
