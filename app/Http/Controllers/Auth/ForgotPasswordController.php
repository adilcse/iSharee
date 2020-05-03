<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\User;
use App\Model\EmailVerify as ModelVerify;
use Illuminate\Support\Facades\Hash;

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
        $otp=rand(1000,9999);
        $user=User::where('email',$email)->first();
        if($user){
            if($user->is_email_verified === 1){
                if(is_null(ModelVerify::where('email',$email)->first())){
                    ModelVerify::insert(['email'=>$email,'otp'=>$otp]);     
                }
                else{
                    ModelVerify::where('email',$email)->update(['otp'=>$otp]);
                }
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
    public function verify(Request $request)
    {
        # code...
        $email=$request->input('email');
        $password=$request->input('password');
        $otp=$request->input('otp');
        $user=ModelVerify::where('email',$email)->first();
        if($user){
            if($user->otp == $otp){
                $data=User::where('email',$email)
                        ->update(['password'=>Hash::make($password)]);
                        if($data === 1){
                        return json_encode(['data'=>'password set successfull','success'=>true]);
                    }else{
                        return json_encode(['data'=>'password not set','success'=>false]);
                    }
            }else{
                return json_encode(['data'=>'invalid otp','success'=>false]);
            }

        }else{
            return json_encode(['data'=>'invalid email. please resend otp','success'=>false]);
        }
        


    }
}
