<?php
/**
 * Control user's forget password actions
 * PHP version 7.0
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/ForgotPasswordController.php
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\Otp;
use App\Helper\Constants;

/**
 * Reset user's password by sending an otp to registered email and verify user's
 * identity and set new password
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/ForgotPasswordController.php
 */
class ForgotPasswordController extends Controller
{
    /**
     * Promt user to enter email address
     * 
     * @param Request $request http request object
     *
     * @return view reset pasword
     */
    public function showLinkRequestForm(Request $request)
    {
        return view('auth.passwords.reset');
    }

    /**
     * Send otp for verification
     * 
     * @param Request $request http request object
     * 
     * @return string status of otp send or not
     */
    public function send(Request $request)
    {
        $email=$request->input('email');
        $user=User::where('email', $email)->first();
        if ($user) {
            $otp=Otp::emailOtp($email);
            if ($user->is_email_verified === 1 && $otp) {
                Mail::to($email)->send(new EmailVerify($otp));
                return json_encode(
                    ['data'=>Constants::$SUCCESS_OTP_SENT,'success'=>true]
                );
            } else {
                return json_encode(
                    ['data'=>Constants::$ERROR_EMAIL_UNVERIFIED,'success'=>false]
                );
            }
        } else {
            return json_encode(
                ['data'=>Constants::$ERROR_INVALID_USER,'success'=>false]
            );
        }
    }

    /**
     * Verifies user input otp and reset password if otp verifies
     * 
     * @param Request $request object
     * 
     * @return password reset status
     */
    public function verify(Request $request)
    {
        $request->validate(
            [
                'email'=>'email|required',
                'password'=>'string|required|min:6',
                'otp'=>'numeric|required|digits:4'
            ]
        );
        $email=$request->input('email');
        $password=$request->input('password');
        $otp=$request->input('otp');
        //verify otp from already generaed table
        if (Otp::verifyEmailOtp($email, $otp)) {
            $data=User::where('email', $email)
                ->update(['password'=>Hash::make($password)]);
            if (1 === $data) {
                return json_encode(
                    ['data'=>Constants::$PASSWORD_SET_SUCCESS,'success'=>true]
                );
            } else {
                return json_encode(
                    ['data'=>Constants::$ERROR_PASSWORD_NOT_SET,'success'=>false]
                );
            }
        } else {
                return json_encode(
                    ['data'=>Constants::$ERROR_OTP_INVALID,'success'=>false]
                );
        }     
    }
}
