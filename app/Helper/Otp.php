<?php
namespace App\Helper;
use App\Model\MobileVerify;

class Otp 
{
    public static function mobileOtp($number)
    {
        $otp=rand(1000,9999);
        try{
            if(is_null(MobileVerify::where('number',$number)->first())){
                MobileVerify::create(['number'=>$number,'otp'=>$otp]);     
            }
            else{
                MobileVerify::where('number',$number)->update(['otp'=>$otp]);
            }
            return $otp;
        }catch(Exception $e){
            return false;
        }

    }
    
    public static function verifyMobileOtp($mobile,$otp)
    {
        $verify=MobileVerify::where('mobile',$mobile)->first();
        if(is_null($verified) || $otp !=$verify->otp){
            return false;
        }
        else{
            $verify->delete();
            return true;
        }
    }
}

