<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\User;
use App\Helper\Otp;

class VerifyEmailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */



    /**
     * Where to redirect users after verification.
     *
     * @var string
     */


    public function email(Request $request)
    {
        $to=$request->input('email');
        $user=User::where('email',$to)->first();
        if($user){
            if($request->input('resend') == 'false'){
                return view('auth.verify',['email'=>$to,'error'=>$request->input('error')]);
            }
            if($user->is_email_verified === 0){
                $otp=Otp::emailOtp($to);
                if($otp){
                    Mail::to($to)->send(new EmailVerify($otp));
                    return view('auth.verify',['email'=>$to]);
                }
                return redirect()->back()->withErrors(['otp'=>'error in generating otp']);
            }
            else{
                return redirect(route('login'));
            }
        }
        else{
            return redirect(route('register'));
        }
        
    }
    public function otp(Request $request)
    {
        $mail=$request->input('mail');
        $otp=$request->input('otp');
        if(Otp::verifyEmailOtp($mail,$otp)){
            User::where('email',$mail)->update(['is_email_verified'=>1,'is_active'=>1]);
            $email=urlencode($mail);
            return redirect(route('login')."?verify=success&email=".$email);
        }
        $email=urlencode($mail);
        return redirect("/email/verify?email=".$email."&resend=false")->withErrors(['otp'=>'invalid otp']);
    }
}
