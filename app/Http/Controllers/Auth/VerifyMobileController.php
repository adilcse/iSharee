<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Helper\Otp;
use App\User;

/**
 * handel mobile verification controller
 */
class VerifyMobileController extends Controller
{

    protected $client;
    /**
     * setup twilio mobile verificaation account
     */
    public function __construct()
    {
        $this->client = new Client($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
    }

    /**
     * send otp to mobile number specified in Request
     * @param request
     * @return verification psge
     */
    public function index(Request $request)
    {
        $request->validate(['number'=>'numeric|required']);
        $mobile=$request->input('number');
        if(strlen($mobile) === 10 && $request->input('resend') === 'true'){
            $otp=Otp::mobileOtp($mobile);
            if($otp){
                //generate otp and send it to users mobile number
                $this->send_sms($otp,$mobile);
                return view('auth.verifyMobile',['mobile'=>$mobile]);
            }
            return view('auth.verifyMobile',['mobile'=>$mobile])->withErrors(['otp'=>'sending otp failed']);
        }
        else{
            //if error occured then response with error message
            $previousUrl = app('url')->previous();
            if(strlen($mobile) != 10){
                return redirect($previousUrl.'&resend=false')->withErrors(['mobile'=>'invalid number']);
            }
            return view('auth.verifyMobile',['mobile'=>$mobile]);
        }
    }

    /**
     * verifies user's entered otp with generated otp
     * @param request
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp'=>'required|numeric|between:1000,9999',
                            'mobile'=>'numeric']);
        // verify otp entered by user and update mobile number status
        if(Otp::verifyMobileOtp($request->input('mobile'),$request->input('otp'))){
            User::where('mobile',$request->input('mobile'))->update(['is_mobile_verified'=>1]);
            return redirect(route('login'))->with('success','mobile verified');
        }
        $previousUrl = app('url')->previous();
        return redirect($previousUrl.'&resend=false')->withErrors(['otp'=>'invalid otp']);
    }

    /**
     * send sms to mobile number with twilio client function
     */
    private function send_sms($otp,$mobile)
    {
        $this->client->messages->create(
            '+917978689252',
            array(
                'from' => "+12058436330",
                'body' => 'Your verification code for'.$mobile.' is '.$otp
            )
        );
    }
}
