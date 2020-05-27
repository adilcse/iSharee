<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
/**
 * handles admin user related actions
 * isAdmin middleware is applied to allow only admin access
 * 
 */
class AdminUserController extends Controller
{
    /**
     * diplay profile of any user
     * admin can view profile of any user
     * @param request object
     * @param id of user
     * @return view with user details
     */
    public function userView(Request $request,$id)
    {
        try{
            //gets user with specified id
            $user=User::find($id);
            if(is_null($user)){
                return redirect()->back()->withErrors(['user not found']);
            }
            return view('admin.profile',['profile'=>$user]);
        }catch(Exception $e){
            return view('error',['message'=>'error in getting user']);
        }
    }

    /**
     * update user status
     * @param request object
     * @param id of the user whose status is to be updated
     * @return response
     */
    public function userStatusUpdate(Request $request,$id)
    {
        $status=intval($request->input('status'));
        //check for valid status
        if(1 === $status || 0 === $status){
            $user=User::find($id);
            if(is_null($user)){
                return response(['error'=>true,'message'=>'invalid user input'],403);
            }
            //update user status 
            $user->is_active=$status;
            $user->save();
            return response(['error'=>false,'message'=>'update successfull'],200);
        }else{
            return response(['error'=>true,'message'=>'invalid status'],403);
        }
    }

    /**
     * update user profile data by admin
     * only admin can update these data
     * @param request object 
     * @return redirect with status
     */
    public function userUpdate(Request $request)
    {
        //validate user's input data
        $request->validate([
            'name'=>['string','required','min:4'],
            'mobile'=>['required','digits:10'],
            'email'=>['email','required'],
            'id'=>['string','required'],
            'mobileVerify'=>['boolean','required'],
            'emailVerify'=>['boolean','required']
        ]);
        try{
            //gets user with input id
            $user=User::find($request->input('id'));
            if(is_null($user)){
                //redirect back with error message
                return redirect()->back()->withErrors(['invalid user id']);
            }
            //update user details
            $user->name=$request->input('name');
            $user->email=$request->input('email');
            $user->mobile=$request->input('mobile');
            $user->is_mobile_verified=$request->input('mobileVerify');
            $user->is_email_verified=$request->input('emailVerify');
            $user->save();
            //return with success message
            return redirect()->back()->with(['status'=>'success']);
        }
        catch(Exception $e){
            return view('error',['message'=>'error in getting user']);
        }
    }
}
