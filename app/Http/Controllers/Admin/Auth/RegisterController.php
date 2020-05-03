<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    protected $redirect= RouteServiceProvider::ADMIN;

    public function showRegisterForm(Request $request)
    {
        return view('auth.register',['admin'=>true]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'mobile'=>['required','string','max:10','min:10','unique:admins'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }
    protected function create(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);
    }

            /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try{
            $this->create($request->all());
        return redirect($this->redirect);
    }catch(Exception $e){
        return redirect()->back();
    }
}

}
