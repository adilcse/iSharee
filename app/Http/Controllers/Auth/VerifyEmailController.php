<?php
/**
 * Verify users email address for registration
 * PHP version 7.0
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/VerifyEmailController.php
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\Model\User;
use App\Helper\Otp;
use App\Helper\Constants;

/**
 * Control Verify email  action of user
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/VerifyEmailController.php
 */
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
     * Verify email id after registration
     *
     * @param Request $request http request
     * 
     * @return redirect to login page
     */
    public function email(Request $request)
    {
        $request->validate(
            [
            'email'=>'email|required'
            ]
        );
        $to=$request->input('email');
        $user=User::where('email', $to)->first();
        //send otp if user exist
        if ($user) {
            if ($request->input('resend') == 'false') {
                return view(
                    'auth.verify',
                    ['email'=>$to,'error'=>$request->input('error')]
                );
            }
            if (0 === $user->is_email_verified) {
                $otp=Otp::emailOtp($to);
                //generate otp and sent it to user via mail
                if ($otp) {
                    Mail::to($to)->send(new EmailVerify($otp));
                    return view('auth.verify', ['email'=>$to]);
                }
                //return to error page
                return redirect()
                    ->back()
                    ->withErrors(['otp'=>Constants::$ERROR_WRONG]);
            } else {
                return redirect(route('login'));
            }
        } else {
            return redirect(route('register'));
        }
        
    }

    /**
     * Verify user otp and email address
     * 
     * @param Request $request http request object 
     * 
     * @return redirect to login after verofocation
     */
    public function otp(Request $request)
    {
        $request->validate(
            [
            'mail'=> 'email|required',
            'otp'=>'numeric|between:1000,9999|required'
            ]
        );
        $mail=$request->input('mail');
        $otp=$request->input('otp');
        if (Otp::verifyEmailOtp($mail, $otp)) {
            //update email status when email and otp verifies
            User::where('email', $mail)->update(['is_email_verified'=>1]);
            $email=urlencode($mail);
            return redirect(route('login')."?verify=success&email=".$email);
        }
        //invalid otp response for invalid otp
        $email=urlencode($mail);
        return redirect("/email/verify?email=".$email."&resend=false")
            ->withErrors(['otp'=>Constants::$ERROR_OTP_INVALID]);
    }
}
