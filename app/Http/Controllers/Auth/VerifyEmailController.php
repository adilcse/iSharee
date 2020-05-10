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
     * verify email id after registration
     *
     * @param request
     */
    public function email(Request $request)
    {
        $request->validate([
            'email'=>'email|required'
        ]);
        $to=$request->input('email');
        $user=User::where('email',$to)->first();
        //send otp if user exist
        if($user){
            if($request->input('resend') == 'false'){
                return view('auth.verify',['email'=>$to,'error'=>$request->input('error')]);
            }
            if($user->is_email_verified === 0){
                $otp=Otp::emailOtp($to);
                //generate otp and sent it to user via mail
                if($otp){
                    Mail::to($to)->send(new EmailVerify($otp));
                    return view('auth.verify',['email'=>$to]);
                }
                //return to error page
                return redirect()->back()->withErrors(['otp'=>'error in generating otp']);
            }
            else{
                return redirect(route('login'));
            }
        }
        //register if user not exist
        else{
            return redirect(route('register'));
        }
        
    }

    /**
     * verify user otp and email address
     * @param request
     */
    public function otp(Request $request)
    {
        $request->valdate([
            'mail'=> 'email|required',
            'otp'=>'numeric|betweem:1000,9999|required'
        ]);
        $mail=$request->input('mail');
        $otp=$request->input('otp');
        if(Otp::verifyEmailOtp($mail,$otp)){
            //update email status when email and otp verifies
            User::where('email',$mail)->update(['is_email_verified'=>1,'is_active'=>1]);
            $email=urlencode($mail);
            return redirect(route('login')."?verify=success&email=".$email);
        }
        //invalid otp response for invalid otp
        $email=urlencode($mail);
        return redirect("/email/verify?email=".$email."&resend=false")->withErrors(['otp'=>'invalid otp']);
    }
}
