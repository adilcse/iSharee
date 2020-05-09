<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifyMobileController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['number'=>'numeric|required']);
        $mobile=$request->input('number');
        
        return view('auth.verifyMobile',['mobile'=>$mobile]);
    }
}
