<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;

class GoogleLoginController extends Controller
{
        /**
     * handle google login button click
     */
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleLoginCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser=User::where('email',$user->email)->first();
        if($existingUser){
            $existingUser->oauth_token=$user->token;
            $existingUser->save();
            Auth::loginUsingId($existingUser->id);
        }else{
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
