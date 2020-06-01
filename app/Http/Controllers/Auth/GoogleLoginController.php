<?php
/**
 * Control google login actions
 * PHP version 7.0
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/GoogleLoginController.php
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\Model\User;
use Auth;

/**
 * User signin via google login
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/GoogleLoginController.php
 */
class GoogleLoginController extends Controller
{
    /**
     * Handle google login button click
     * 
     * @return redirect to google signin page
     */
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Called when user autherize google signin for this app
     * 
     * @return redirect to home after login
     */
    public function googleLoginCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser=User::where('email', $user->email)->first();
        if ($existingUser) {
            $existingUser->oauth_token=$user->token;
            $existingUser->save();
            Auth::loginUsingId($existingUser->id);
        } else {
            $newUser=new User;
            $newUser->name = $user->name;
            $newUser->oauth_token=$user->token;
            $newUser->email = $user->email;
            $newUser->password = $user->token;
            $newUser->is_email_verified = $user->user['verified_email'];
            $newUser->save();
            Auth::loginUsingId($newUser->id);
        }
        return redirect(route('home'));
    }
}
