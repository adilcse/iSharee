<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Helper\Otp;
use App\User;

class VerifyMobileController extends Controller
{

    protected $client;
    public function __construct()
    {
        $this->client = new Client($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
    }
    public function index(Request $request)
    {
        $request->validate(['number'=>'numeric|required']);
        $mobile=$request->input('number');
        if(strlen($mobile) === 10 && $request->input('resend') === 'true'){
            $otp=Otp::mobileOtp($mobile);
            if($otp){
                $this->send_sms($otp,$mobile);
                return view('auth.verifyMobile',['mobile'=>$mobile]);
            }
            return view('auth.verifyMobile',['mobile'=>$mobile])->withErrors(['otp'=>'sending otp failed']);
        }
        else{
            $previousUrl = app('url')->previous();
            if(strlen($mobile) != 10){
                return redirect($previousUrl.'&resend=false')->withErrors(['mobile'=>'invalid number']);
            }
            return view('auth.verifyMobile',['mobile'=>$mobile]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp'=>'required|numeric|between:1000,9999',
                            'mobile'=>'numeric']);
        if(Otp::verifyMobileOtp($request->input('mobile'),$request->input('otp'))){
            User::where('mobile',$request->input('mobile'))->update(['is_mobile_verified'=>1]);
            return redirect(route('login'))->with('success','mobile verified');
        }
        $previousUrl = app('url')->previous();
        return redirect($previousUrl.'&resend=false')->withErrors(['otp'=>'invalid otp']);
    }

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
