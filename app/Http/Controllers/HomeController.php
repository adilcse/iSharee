<?php
/**
 * Control home actions
 * PHP version: 7.0
 * 
 * @category Controller
 * @package  Http/Controller
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/HomeController.php
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Category;
use App\Model\ArticleCategory;
use App\Model\User;
use App\Helper\Constants;
use Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Control user home page
 * 
 * @category Controller
 * @package  Http/Controller
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/HomeController.php
 */
class HomeController extends Controller
{
    // items will be displayed per page
    protected $per_page;

    /**
     * Create a new controller instance.
     * set per page items in home page from env
     * 
     * @return void
     */
    public function __construct()
    {
        $this->per_page=intval(env('ITEMS_PER_PAGE', 10));
    }

    /**
     * Show the application home page.
     * 
     * @param Request $request object
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        $articles=Article::where('articles.is_published', 1)
                            ->orderby('views', 'desc')
                            ->paginate($this->per_page);
        
        return view('home', ['articles'=>$articles]);
    }
    
    /**
     * Category page.
     * Display article belongs to a given category
     * 
     * @param string $id name of the category to be displayed
     * 
     * @return home view with artcles belongs to a category
     */
    public function category($id)
    {
        try{
            //find the category
            $category=Category::where('slug', $id)->first();
            $articles=$category->articles()
                ->where('articles.is_published', 1)
                ->orderby('views', 'desc')
                ->paginate($this->per_page);
            return view('home', ['articles'=>$articles, 'category'=>$category]);
        }
        catch(Exception $e){
            return view('error', ['message'=>Constants::$ERROR_CATAGORY]);
        }
        
    }

    /**
     * Display all artice published by a user
     * 
     * @param Request $request oblect
     * 
     * @return view home|error for requisted user
     */
    public function myArticle(Request $request)
    {
        //find user id and fetch all article of the user
        $userId=$request->user()->id;
        if (!is_null($userId)) {
            $articles=Article::where('user_id', $userId)
                        ->orderby('views', 'desc')
                        ->paginate($this->per_page);
            //return home view with fetched articles
            return view('home', ['articles'=>$articles,'myArticle'=>true]);
        }
        return view('error', ['message'=>'user not logged in']);
    }

    /**
     * Dispay articles of any perticlular user
     * 
     * @param Request $request object
     * @param int     $id      of the user whose article are to be displayed
     * 
     * @return home view with user's articles
     */
    public function userArticles(Request $request,$id)
    {
        $user=User::find($id);
        if (is_null($user)) {
            return redirect()->back()->withErrors(['user does not exist']);
        }
        //admin can view all published articles of the user 
        if (Auth::user()->is_admin) {
            $articles=$user->articles()->paginate($this->per_page);
        } else {
            //otheruser can only view published articles
            $articles=$user->articles()
                ->where('is_published', 1)
                ->paginate($this->per_page);
        }
        if ($articles->items() < 1) {
            return view('home', ['articles'=>null]);
        }
        return view('home', ['articles'=>$articles,'name'=>$user->name]);
    }

    /**
     * User can update name of his/her profile
     *  
     * @param Request $request object
     * 
     * @return redirect with proper message
     */
    public function userUpdate(Request $request)
    {
        $request->validate(
            [
                'name'=>'required|string|max:50|min:3',
                'id'=>'numeric|required',
                'mobile'=>'numeric|nullable|digits:10',
                'email'=>'nullable|email|uqique:users',
                'newPassword'=>'nullable|string|min:6',
                'cPassword'=> 'nullable|string|min:6',
                'oldPassword'=> 'nullable|string|min:6'
            ]
        );
        //fetch the user update name and return with response
        try{
            $user=Auth::user();
            $user->name=$request->input('name');
            if (!$user->is_email_verified && $request->email !== $user->email) {
                $user->email = $request->email;
            }
            if (!$user->is_mobile_verified && $request->mobile != $user->mobile) {
                $user->mobile=$request->mobile;
            }
            if ($request->changePasswordCheck) {
                if ($request->cPassword !== $request->newPassword) {
                    return redirect()
                        ->back()
                        ->withErrors(['password'=>Constants::$ERROR_PASSWORD_MATCH]);
                } else if (Auth::user()->oauth_token) {
                    $user->password = Hash::make($request->newPassword);
                    $user->oauth_token = null;
                } else {
                    if (Hash::check($request->oldPassword, Auth::user()->password)) {
                        $user->password = Hash::make($request->newPassword);
                        $user->oauth_token = null;
                    } else {
                        return redirect()
                            ->back()
                            ->withErrors(
                                ['password'=>Constants::$ERROR_PASSWORD_INVALID]
                            );
                    }
                }
            }
            $user->save();
            return redirect()->back()->with(['status'=>'success']);
        }
        catch(Exception $e){
            return view('error', ['message'=>'can not update name']);
        }
        
    }
}
