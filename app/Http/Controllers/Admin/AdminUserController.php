<?php
/**
 * Handles admin user related actions
 * PHP version: 7.0
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/AdminUserController.php
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Helper\Constants;
/**
 * Handles admin user related actions
 * isAdmin middleware is applied to allow only admin access
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/AdminUserController.php
 */
class AdminUserController extends Controller
{
    /**
     * Diplay profile of any user
     * admin can view profile of any user
     * 
     * @param Request $request object
     * @param int     $id      of user
     * 
     * @return view with user details
     */
    public function userView(Request $request,$id)
    {
        try{
            //gets user with specified id
            $user=User::find($id);
            if (is_null($user)) {
                return redirect()
                    ->back()
                    ->withErrors([Constants::$ERROR_USER_NOT_FOUND]);
            }
            return view('admin.profile', ['profile'=>$user]);
        }catch(Exception $e){
            return view('error', ['message'=>Constants::$ERROR_GETTING_USER]);
        }
    }

    /**
     * Update user status
     * 
     * @param Request $request http request object
     * @param int     $id      id of user to be updated
     * 
     * @return response with proper message
     */
    public function userStatusUpdate(Request $request,$id)
    {
        $status=intval($request->input('status'));
        //check for valid status
        if (1 === $status || 0 === $status) {
            $user=User::find($id);
            if (is_null($user)) {
                return response(
                    ['error'=>true,'message'=>Constants::$ERROR_INVALID_USER],
                    403
                );
            }
            //update user status 
            $user->is_active=$status;
            $user->save();
            return response(
                ['error'=>false, 'message'=>$SUCCESS_UPDATED],
                200
            );
        } else {
            return response(
                ['error'=>true,'message'=>Constants::$ERROR_STATUS_UPDATE],
                403
            );
        }
    }

    /**
     * Update user profile data by admin
     * only admin can update these data
     * 
     * @param Request $request object 
     * 
     * @return redirect with status
     */
    public function userUpdate(Request $request)
    {
        //validate user's input data
        $request->validate(
            [
                'name'=>['string','required','min:4'],
                'mobile'=>[
                    'required',
                    'digits:10',
                    'unique:users,mobile,'.$request->input('id')
                ],
                'email'=>[
                    'email',
                    'required',
                    'unique:users,email,'.$request->input('id')],
                'id'=>['string','required'],
                'mobileVerify'=>['boolean','required'],
                'emailVerify'=>['boolean','required']
            ]
        );
        try{
            //gets user with input id
            $user=User::find($request->input('id'));
            if (is_null($user)) {
                //redirect back with error message
                return redirect()
                    ->back()
                    ->withErrors([Constants::$ERROR_INVALID_USER]);
            } 
            //update user details
            $user->name=$request->input('name');
            $user->email=$request->input('email');
            $user->mobile=$request->input('mobile');
            $user->is_mobile_verified=$request->input('mobileVerify');
            $user->is_email_verified=$request->input('emailVerify');
            $user->save();
            //return with success message
            return redirect()->back()->with(['status'=>Constants::$SUCCESS_MSG]);
        }
        catch(Exception $e){
            return view('error', ['message'=>Constants::$ERROR_GETTING_USER]);
        }
    }
}
