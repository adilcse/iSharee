<?php
namespace App\Helper;
use App\Model\MobileVerify;
use App\Model\EmailVerify;
/**
 * generate otp for mobile and email verification.
 * Stores otp in separage table and verifies when user enters otp.
 * return verificatiion status.
 */
class Otp 
{
    /**
     * generate mobile otp and save it in database.
     * @param number for which otp is generated
     * @return otp generated
     */
    public static function mobileOtp($number)
    {
        //generate random otp
        $otp=rand(1000,9999);
        //if otp is not generated then create a new record with number and otp
        try{
            if(is_null(MobileVerify::where('number',$number)->first())){
                MobileVerify::create(['number'=>$number,'otp'=>$otp]);     
            }
            //otherwise update the current record with new otp
            else{
                MobileVerify::where('number',$number)->update(['otp'=>$otp]);
            }
            //returns the new generated otp
            return $otp;
            //if otp generation failed return false
        }catch(Exception $e){
            return false;
        }
    }
    
    /**
     * verify user's input otp with the generated otp
     * @param mobile number of the user
     * @param otp entered by the user
     * @return true|false 
     */
    public static function verifyMobileOtp($mobile,$otp)
    {
        //get user number and verify otp from database
        $verify=MobileVerify::where('number',$mobile)->first();
        if(is_null($verify) || $otp != $verify->otp){
            return false;
        }
        else{
            //if otp verified delete the record and return true
            $verify->delete();
            return true;
        }
    }

    /**
     * generate otp for email verification
     * @param email address for the user for which email is to be generated
     * @return otp generated
     */
    public static function emailOtp($email)
    {
        //random 4 digit otp generate
        $otp=rand(1000,9999);
        try{
            //if email already exist then update the otp otherwise create new record 
            //and save otp
            if(is_null(EmailVerify::where('email',$email)->first())){
                EmailVerify::create(['email'=>$email,'otp'=>$otp]);     
            }
            else{
                EmailVerify::where('email',$email)->update(['otp'=>$otp]);
            }
            return $otp;
        }catch(Exception $e){
            //if exception occur the return false
            return false;
        }
    }

/**
 * verify email otp from email verify table
 * return true if email and otp verifies otherwise return false
 * @param email of the user
 * @param otp entered by the user
 * @return true|false if otp verifies or fails
 */

    public static function verifyEmailOtp($email,$otp)
    {
        try{
            $verify=EmailVerify::where('email',$email)->first();
            //if otp verifies then clear therecord and return true
            if(!is_null($verify) && $otp == $verify->otp){
                $verify->delete();
                return true;
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }

}

