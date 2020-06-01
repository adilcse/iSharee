<?php
/**
 * Verify users email address for registration
 * PHP version 7.0
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/VerifyMObileController.php
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Helper\Otp;
use App\Helper\Constants;
use App\Model\User;
use Auth;
/**
 * Handel mobile verification controller
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/VerifyMObileController.php
 */
class VerifyMobileController extends Controller
{

    protected $client, $registered_number,$sender_number,$registered_number_extra;
    /**
     * Setup twilio mobile verification account
     * 
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
        $this->registered_number = $_ENV['TWILIO_REGISTERED_NUMBER'];
        $this->registered_number_extra = $_ENV['TWILIO_REGISTERED_NUMBER_EXTRA'];
        $this->sender_number = $_ENV['TWILIO_MOBILE_NUMBER'];
    }

    /**
     * Send otp to mobile number specified in Request
     * 
     * @param Requst $request http object
     * 
     * @return verification psge
     */
    public function index(Request $request)
    {

        $request->validate(['number'=>'numeric|required']);
        $mobile=$request->input('number');
        if (Auth::check()) {
            $user=Auth::user();
            $user->mobile=$mobile;
            $user->save();
        }
        if (strlen($mobile) === 10 && $request->input('resend') === 'true') {
            $otp=Otp::mobileOtp($mobile);
            if ($otp) {
                //generate otp and send it to users mobile number
                $this->_sendSms($otp, $mobile);
                return view('auth.verifyMobile', ['mobile'=>$mobile]);
            }
            return view('auth.verifyMobile', ['mobile'=>$mobile])
            ->withErrors(['otp'=>Constants::$ERROR_OTP_SEND]);
        } else {
            //if error occured then response with error message
            $previousUrl = app('url')->previous();
            if (strlen($mobile) != 10) {
                return redirect($previousUrl.'&resend=false')
                    ->withErrors(['mobile'=>Constants::$ERROR_MOBILE_INVALID]);
            }
            return view('auth.verifyMobile', ['mobile'=>$mobile]);
        }
    }

    /**
     * Verifies user's entered otp with generated otp
     * 
     * @param Request $request http request object
     * 
     * @return redirect route
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(
            ['otp'=>'required|numeric|between:1000,9999','mobile'=>'numeric']
        );
        // verify otp entered by user and update mobile number status
        $verified = Otp::verifyMobileOtp(
            $request->input('mobile'), 
            $request->input('otp')
        );
        if ($verified) {
            User::where('mobile', $request->input('mobile'))
                ->update(['is_mobile_verified'=>1]);
            return redirect(route('login'))
                ->with('success', Constants::$SUCCESS_MOBILE_VERIFIED);
        }
        $previousUrl = app('url')->previous();
        return redirect($previousUrl.'&resend=false')
            ->withErrors(['otp'=>Constants::$ERROR_OTP_INVALID]);
    }

    /**
     * Send sms to mobile number with twilio client function
     * 
     * @param int $otp    to be sent to user
     * @param int $mobile user's number
     * 
     * @return void
     */
    private function _sendSms($otp,$mobile)
    {
        $to= ltrim($this->registered_number_extra, "+91") === $mobile
            ?$this->registered_number_extra
            :$this->registered_number;
        $this->client->messages->create(
            $to,
            array(
                'from' => $this->sender_number,
                'body' => 'Your verification code for'.$mobile.' is '.$otp
            )
        );
    }
}
