<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Article;
use App\User;
use Auth;
class AdminController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $per_page;

    public function __construct()
    {
        $this->per_page=intval(env('ADMIN_TABLE_PER_PAGE',5));
    }

    public function index(Request $request,$table=null)
    {
        # code...
        $view=$request->input('view');
        if(is_null($table)){
            $articles=$this->getArticles($request);
            $users=$this->getUsers($request);
        }else if('article' === $table){
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
            $users=$this->getUsers($request);
        }else if('users' === $table){
            if(isset($view)){
                switch($view){
                    case "2":
                        $users=$this->getUsers($request,0);
                    break;
                    case "3":
                        $users=$this->getUsers($request,1);
                    break;
                    default:
                    $users=$this->getUsers($request);
                }
            }else{
                $users=$this->getUsers($request);
            }
            $articles=$this->getArticles($request);
        }
        return view('admin.dashboard',['users'=>$users,'articles'=>$articles,'view'=>$view]);
    }

    private function getArticles($request,$view=null){
        $url=$request->url();
        $url=rtrim($url,"/article");
        if(is_null($view)){
            $article=Article::orderby('created_at','desc');
        }else{
            $article=Article::where('is_published',$view)
                    ->orderby('created_at','desc');
        }
        return $article ->paginate($this->per_page)->withPath($url."/article");
    }

    public function getUsers($request,$active=null)
    {
        # code...
        $url=$request->url();
        $url=rtrim($url,"/users");
        if(is_null($active)){
            $users=User::where('is_admin',0)
                    ->orderby('created_at','desc');
        }else{
            $users=Article::where('is_admin',0)
                    ->where('is_active',$active)
                    ->orderby('created_at','desc');
        }
        return $users->withCount('articles')->paginate($this->per_page)->withPath($url."/users");
    }

    public function userUpdate(Request $request)
    {
        $user=Auth::user();
        if($user->is_admin){
            $status=intval($request->input('status'));
            if(1 === $status || 0 === $status){
                $user->is_active=$status;
                $user->save();
                return response(['error'=>false,'message'=>'update successfull'],200);
            }else{
                return response(['error'=>true,'message'=>'invalid status'],403);
            }
        }
        else{
            return response(['error'=>true,'message'=>'you are not admin'],403);
        }
    }
/**
 * update article status and return status of article
 */
    public function articleUpdate(Request $request,$id)
    {

        $article=Article::find($id);
        if(is_null($article)){
            return response(['status'=>false,'message'=>'invalid article id'],404);    
        }
        $ip=intval($request->input('status'));
        if($ip=== 1 || $ip===0){
            $article->is_published=$ip;
            $article->save();
            return response(['status'=>true,'message'=>'updated'],200);     
        }
        return response(['status'=>false,'message'=>'invalid status update'],403);
    }
    public function profile()
    {
        return view('admin.dashboard');
        # code...
    }
}
