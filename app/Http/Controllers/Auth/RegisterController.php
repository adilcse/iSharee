<?php
/**
 * Control user's register actions
 * PHP version 7.0
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/RegisterController.php
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Model\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

/**
 * Register user with basic details like name, email, mobile number and password 
 * 
 * @category Auth
 * @package  Http/Controller/Auth
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Auth/RegisterController.php
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::VERIFY_EMAIL;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data user data to be validated
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data, 
            [
                'name' => ['required', 'string', 'max:255'],
                'mobile'=>['required','string','max:10','min:10','unique:users'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data new user details
     * 
     * @return \App\Model\User
     */
    protected function create(array $data)
    {
        return User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
            ]
        );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request http object
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        try{
            event(new Registered($user = $this->create($request->all())));
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            //handle exception
        }
        $email=urlencode($user->email);
        if (env('ALLOW_EMAIL', false)) {
            return $this->registered($request, $user)
            ?: redirect($this->redirectPath().'?email='.$email);
        }
        return $this->registered($request, $user)
        ?:redirect(route('login'));
        
    }
}
