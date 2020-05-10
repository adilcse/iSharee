<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Catagory;
use App\Model\ArticleCatagory;
use App\User;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $per_page;

    public function __construct()
    {
        $this->per_page=intval(env('ITEMS_PER_PAGE',5));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        $articles=Article::where('articles.is_published',1)
                            ->orderby('views','desc')
                            ->paginate($this->per_page);
        
        return view('home',['articles'=>$articles]);
    }

    public function catagory($id)
    {
        try{
            $catagory=Catagory::where('slug',$id)->first();
        $articles=$catagory->articles()
        ->where('articles.is_published',1)
        ->orderby('views','desc')
        ->paginate($this->per_page);

        return view('home',['articles'=>$articles,'catagory'=>$catagory]);
        }
        catch(Exception $e){
            return view('error',['message'=>'catagory view failed']);
        }
        
    }

    public function myArticle(Request $request)
    {
        $userId=$request->user()->id;
        if(!is_null($userId)){

            $articles=Article::where('user_id',$userId)
                        ->orderby('views','desc')
                        ->paginate($this->per_page);

            return view('home',['articles'=>$articles,'myArticle'=>true]);
        }
        return view('error',['message'=>'user not logged in']);
    }

    public function userArticles(Request $request,$id)
    {
        $user=User::find($id);
        if(is_null($user)){
            return redirect()->back()->withErrors(['user does not exist']);
        }
        $articles=$user->articles;
        if(is_null($articles)){
            return view('home',['articles'=>null]);
        }
        $articles=$user->articles()->where('is_published',1)
                        ->paginate($this->per_page);
        return view('home',['articles'=>$articles,'name'=>$user->name]);
    }

    public function userUpdate(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:50|min:3',
            'id'=>'numeric|required'
        ]);
        $user=Auth::user();
        $user->name=$request->input('name');
        $user->save();
        return redirect()->back()->with(['status'=>'success']);
    }
}
