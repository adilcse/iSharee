<?php
/**
 * Send and verify Otp for user
 * PHP version: 7.0
 * 
 * @category Helper
 * @package  App/Helper
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Helper/Otp.php
 */

namespace App\Helper;

use App\Model\MobileVerify;
use App\Model\EmailVerify;

/**
 * Generate otp for mobile and email verification.
 * Stores otp in separage table and verifies when user enters otp.
 * return verificatiion status.
 * 
 * @category Helper
 * @package  App/Helper
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Helper/Otp.php
 */
class Otp
{
    /**
     * Generate mobile otp and save it in database.
     * 
     * @param int $number for which otp is generated
     * 
     * @return int $otp generated code
     */
    public static function mobileOtp($number)
    {
        //generate random otp
        $otp=rand(1000, 9999);
        //if otp is not generated then create a new record with number and otp
        try{
            if (is_null(MobileVerify::where('number', $number)->first())) {
                MobileVerify::create(['number'=>$number,'otp'=>$otp]);     
            } else {
                MobileVerify::where('number', $number)->update(['otp'=>$otp]);
            }
            //returns the new generated otp
            return $otp;
            //if otp generation failed return false
        }catch(Exception $e){
            return false;
        }
    }
    
    /**
     * Verify user's input otp with the generated otp
     * 
     * @param int $mobile number of the user
     * @param int $otp    entered by the user
     * 
     * @return boolean verified or not
     */
    public static function verifyMobileOtp($mobile,$otp)
    {
        //get user number and verify otp from database
        $verify=MobileVerify::where('number', $mobile)->first();
        if (is_null($verify) || $otp != $verify->otp) {
            return false;
        } else {
            //if otp verified delete the record and return true
            $verify->delete();
            return true;
        }
    }

    /**
     * Generate otp for email verification
     * 
     * @param srting $email address for the user for which email is to be generated
     * 
     * @return int $otp generated
     */
    public static function emailOtp($email)
    {
        //random 4 digit otp generate
        $otp=rand(1000, 9999);
        try{
            //if email already exist then update the otp otherwise create new record 
            //and save otp
            if (is_null(EmailVerify::where('email', $email)->first())) {
                EmailVerify::create(['email'=>$email,'otp'=>$otp]);     
            } else {
                EmailVerify::where('email', $email)->update(['otp'=>$otp]);
            }
            return $otp;
        }catch(Exception $e){
            //if exception occur the return false
            return false;
        }
    }

    /**
     * Verify email otp from email verify table
     *
     * @param string $email of the user
     * @param int    $otp   entered by the user
     * 
     * @return boolean true|false if otp verifies or fails
     */
    public static function verifyEmailOtp($email,$otp)
    {
        try{
            $verify=EmailVerify::where('email', $email)->first();
            //if otp verifies then clear therecord and return true
            if (!is_null($verify) && $otp == $verify->otp) {
                $verify->delete();
                return true;
            } 
            return false;
        }catch(Exception $e){
            return false;
        }
    }

}

