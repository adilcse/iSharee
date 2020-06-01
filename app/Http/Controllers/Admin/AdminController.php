<?php
/**
 * Handles admin actions
 * PHP version: 7.0
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/AdminController.php
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Comments;
use App\Model\User;
use Auth;

/**
 * Handles all admin dashboard requests
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/AdminController.php
 */
class AdminController extends Controller
{
    protected $per_page;

    /**
     * Create a new controller instance.
     * setup the number of page records dispay in admin tables.
     *
     * @return void
     */
    public function __construct()
    {
        $this->per_page=intval(env('ADMIN_TABLE_PER_PAGE', 5));
    }

    /**
     * Fetch all details table and return to admin dashbord page
     * 
     * @param Request $request http request object 
     * @param strint  $table   (optional) whose content is to be changed
     * 
     * @return view admin homepage view
     */
    public function index(Request $request,$table=null)
    {
        //get the current view of the user and article tables
        $view=$request->input('view');
        $userview=$request->input('userview');
        if (is_null($table)) {
            //return default view for all the tables
            $articles=$this->_getArticles($request);
            $users=$this->_getUsers($request);
            $comments=$this->_getComments($request);
        } else if ('article' === $table) {
            //change article view 
            if (isset($view)) {
                switch($view){
                case "2":
                    $articles=$this->_getArticles($request, 0);
                    break;
                case "3":
                    $articles=$this->_getArticles($request, 1);
                    break;
                default:
                    $articles=$this->_getArticles($request);
                }
            } else {
                $articles=$this->_getArticles($request);
            }
            //default view for other table
            $request->merge(['page'=>1]);
            $users=$this->_getUsers($request);
            $comments=$this->_getComments($request);
        } else if ('user' === $table) {
            //change view for user table
            if (isset($userview)) {
                switch($userview){
                case "2":
                    $users=$this->_getUsers($request, 1);
                    break;
                case "3":
                    $users=$this->_getUsers($request, 0);
                    break;
                default:
                    $users=$this->_getUsers($request);
                }
            } else {
                $users=$this->_getUsers($request);
            }
            //default view for other table
            $request->merge(['page'=>1]);
            $articles=$this->_getArticles($request);
            $comments=$this->_getComments($request);
        } else if ('comments' === $table) {
            //change view for comment table
            $comments=$this->_getComments($request);
            $request->merge(['page'=>1]);
            $articles=$this->_getArticles($request);
            $users=$this->_getUsers($request);
        }
        //returns admin view with all the table data
        return view(
            'admin.dashboard',
            [
                'users'=>$users,
                'articles'=>$articles,
                'view'=>$view,
                'userview'=>$userview,
                'comments'=>$comments
            ]
        );
    }

    /**
     * Helper funtion for index to fetch article and current view of the page
     * 
     * @param Request $request http request object
     * @param string  $view    current view of article table null for all
     * 
     * @return paginated collection of articles sorted with leatest
     */
    private function _getArticles($request,$view=null)
    {
        $url=$this->_getUrl($request);
        try{
            if (is_null($view)) {
                //set article to default view with total likes of a article
                $article=Article::orderby('created_at', 'desc')->withCount('likes');
            } else {
                //get articles with defined view and likes
                $article=Article::where('is_published', $view)
                        ->orderby('created_at', 'desc')->withCount('likes');
            }
            //set url with defined view
            return $article ->paginate($this->per_page)->withPath($url."/article");
        }catch(Exception $e){
            return null;
        }
    }

    /**
     * Helper function for index to get all users
     * gets users from users table with current view defined
     * 
     * @param Request $request object
     * @param string  $active  active|inactive user null for all users
     * 
     * @return paginated user table
     */
    private function _getUsers($request, $active=null)
    {
        $url=$this->_getUrl($request);
        try{
            //gets all the users who is not admin
            if (is_null($active)) {
                $users=User::where('is_admin', 0)
                        ->orderby('created_at', 'desc');
            } else {
                //gets only active|inactive users
                $users=User::where('is_admin', 0)
                        ->where('is_active', $active)
                        ->orderby('created_at', 'desc');
            }
            return $users->withCount('articles')
                ->withCount('likes')
                ->paginate($this->per_page)
                ->withPath($url."/user");
        }catch(Exception $e){
            return null;
        }
    }

    /**
     * Helper function for index gets all guest comments form comments table
     * 
     * @param Request $request http request object
     * 
     * @return array $comments all guest comment in paginated form
     */
    private function _getComments($request)
    {
        $url=$url=$this->_getUrl($request);
        try{
            //gets al lcomment which are not published
            return Comments::where('is_published', 0)
                    ->paginate($this->per_page)
                    ->withPath($url."/comments");
        }
        catch(Exception $e){
            return null;
        }   
    }

    /**
     * Helper funcion for user,comment,article functions
     * trim any extra parimeter
     * 
     * @param Request $request http request object
     * 
     * @return string url after trimming;
     */
    private function _getUrl($request)
    {
        $url=$request->url();
        $url=rtrim($url, "/article");
        $url=rtrim($url, "/user");
        $url=rtrim($url, "/comments");
        return $url;
    }
}
