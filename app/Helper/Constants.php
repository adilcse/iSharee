<?php
/**
 * Constants used in the app
 * PHP version: 7.0
 * 
 * @category Helper
 * @package  App/Helper
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Helper/Constants.php
 */
namespace App\Helper;

/**
 * App Constnts used by other classes
 * 
 * @category Helper
 * @package  App/Helper
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Helper/Constants.php
 */
class Constants
{
    public static $SUCCESS_MSG="Success";
    public static $SUCCESS_UPDATED="updated";
    public static $SUCCESS_DELETE="deleted successfully";
    public static $SUCCESS_CATAGORY_ADD="Catagory added successfully";

    public static $ERROR_FAILED="failed";
    public static $ERROR_ARTICLE_DELETE="Article delete failed";
    public static $ERROR_STATUS_UPDATE="invalid status update";
    public static $ERROR_WRONG="Something went wrong";
    public static $ERROR_INVALID_ARTICLE_ID="Invalid article id";
    public static $ERROR_USER_NOT_FOUND="user not found";
    public static $ERROR_INVALID_USER="Invalid user";
    public static $ERROR_GETTING_USER="error in getting user";
    public static $ERROR_GETTING_CATAGORY="error in getting catagory";
    public static $ERROR_CREATING_CATAGORY="error in creating catagory";
    public static $ERROR_UPDATING_CATAGORY="error in updating catagory";
    public static $ERROR_DELETING_CATAGORY="error in deleting catagory";
    public static $ERROR_INVALID_CATAGORY="Invalid catagory";
    public static $ERROR_UNAUTHORIZED="you are not autherorized";
    public static $ERROR_SLUG_CREATE="can not create slug";
    public static $MESSAGE_ARTICLE_NOT_PUBLISHED="article is not published yet" ;
    public static $MESSAGE_LOGIN_FIRST="Please login first" ;
    public static $MESSAGE_LIKED="Already liked" ;
}
