<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Comments;
use App\User;
use Auth;

/**
 * handles all admin requests
 */
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     * setup the number of page records dispay in admin tables.
     *
     * @return void
     */
    protected $per_page;

    public function __construct()
    {
        $this->per_page=intval(env('ADMIN_TABLE_PER_PAGE',5));
    }

    /**
     * fetch all details table and return to admin dashbord page
     * @param request 
     * @param table (optional) whose content is to be changed
     * @return view admin homepage view
     */
    public function index(Request $request,$table=null)
    {
        //get the current view of the user and article tables
        $view=$request->input('view');
        $userview=$request->input('userview');
        if(is_null($table)){
            //return default view for all the tables
            $articles=$this->getArticles($request);
            $users=$this->getUsers($request);
            $comments=$this->getComments($request);
        }else if('article' === $table){
            //change article view 
            if(isset($view)){
                switch($view){
                    case "2":
                        $articles=$this->getArticles($request,0);
                    break;
                    case "3":
                        $articles=$this->getArticles($request,1);
                    break;
                    default:
                    $articles=$this->getArticles($request);
                }
            }else{
                $articles=$this->getArticles($request);
            }
            //default view for other table
            $request->merge(['page'=>1]);
            $users=$this->getUsers($request);
            $comments=$this->getComments($request);
        }else if('user' === $table){
            //change view for user table
            if(isset($userview)){
                switch($userview){
                    case "2":
                        $users=$this->getUsers($request,1);
                    break;
                    case "3":
                        $users=$this->getUsers($request,0);
                    break;
                    default:
                    $users=$this->getUsers($request);
                }
            }else{
                $users=$this->getUsers($request);
            }
            //default view for other table
            $request->merge(['page'=>1]);
            $articles=$this->getArticles($request);
            $comments=$this->getComments($request);
        }
        else if('comments' === $table){
            //change view for comment table
            $comments=$this->getComments($request);
            $request->merge(['page'=>1]);
            $articles=$this->getArticles($request);
            $users=$this->getUsers($request);
        }
        //returns admin view with all the table data
        return view('admin.dashboard',['users'=>$users,'articles'=>$articles,'view'=>$view,'userview'=>$userview,'comments'=>$comments]);
    }

    /**
     * helper funtion for index to fetch article and current view of the page
     * @param request object
     * @param view current view of article table null for all
     * @return paginated collection of articles sorted with leatest
     */
    private function getArticles($request,$view=null)
    {
        $url=$url=$this->getUrl($request);
        try{
            if(is_null($view)){
                //set article to default view with total likes of a article
                $article=Article::orderby('created_at','desc')->withCount('likes');
            }else{
                //get articles with defined view and likes
                $article=Article::where('is_published',$view)
                        ->orderby('created_at','desc')->withCount('likes');
            }
            //set url with defined view
            return $article ->paginate($this->per_page)->withPath($url."/article");
        }catch(Exception $e){
            return null;
        }
    }
    /**
     * helper function for index 
     * gets users from users table with current view defined
     * @param request object
     * @param active|inactive user null for all users
     * @return paginated user table
     */
    private function getUsers($request,$active=null)
    {
        $url=$this->getUrl($request);
        try{
            //gets all the users who is not admin
            if(is_null($active)){
                $users=User::where('is_admin',0)
                        ->orderby('created_at','desc');
            }else{
                //gets only active|inactive users
                $users=User::where('is_admin',0)
                        ->where('is_active',$active)
                        ->orderby('created_at','desc');
            }
            return $users->withCount('articles')->withCount('likes')->paginate($this->per_page)->withPath($url."/user");
        }catch(Exception $e){
            return null;
        }
    }

    /**
     * helper function for index gets all guest comments form comments table
     * @param request object
     * @return comments all guest comment in paginated form
     */
    private function getComments($request)
    {
        $url=$url=$this->getUrl($request);
        try{
            //gets al lcomment which are not published
            return Comments::where('is_published',0)
                    ->paginate($this->per_page)
                    ->withPath($url."/comments");
        }
        catch(Exception $e){
            return null;
        }   
    }

    /**
     * helper funcion for user,comment,article functions
     * trim any extra parimeter
     */
    private function getUrl($request)
    {
        $url=$request->url();
        $url=rtrim($url,"/article");
        $url=rtrim($url,"/user");
        $url=rtrim($url,"/comments");
        return $url;
    }
}
