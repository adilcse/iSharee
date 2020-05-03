<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\User;
use App\Model\EmailVerify as ModelVerify;
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
        $otp=rand(1000,9999);
        $user=User::where('email',$to)->first();
        if($user){
            if($request->input('resend') == 'false'){
                return view('auth.verify',['email'=>$to,'error'=>$request->input('error')]);
            }
            if($user->is_email_verified === 0){
                if(is_null(ModelVerify::where('email',$to)->first())){
                    ModelVerify::insert(['email'=>$to,'otp'=>$otp]);     
                }
                else{
                    ModelVerify::where('email',$to)->update(['otp'=>$otp]);
                }
                Mail::to($to)->send(new EmailVerify($otp));
                return view('auth.verify',['email'=>$to]);
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
        $data=ModelVerify::where('email',$mail)->first();
        if($data){
            if($otp == $data->otp){
                User::where('email',$mail)->update(['is_email_verified'=>1]);
                $email=urlencode($mail);
                return redirect(route('login')."?verify=success&email=".$email);
            }else{
                $email=urlencode($mail);
                return redirect("/email/verify?email=".$email."&resend=false&error=otp");
            }
        }else{
            return redirect("/email/verify?&resend=false&error=email");
        }
    }
}
