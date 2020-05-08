<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Catagory;
use App\Model\ArticleCatagory;
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
        # code...
        $catagory=Catagory::find($id);
        $articles=$catagory->articles()
        ->where('articles.is_published',1)
        ->orderby('views','desc')
        ->paginate($this->per_page);

        return view('home',['articles'=>$articles,'catagory'=>$catagory]);
    }

    public function myArticle(Request $request)
    {
        $userId=$request->user()->id;
        if(!is_null($userId)){
            
            $articles=Article::where('articles.is_published',1)
                        ->where('user_id',$userId)
                        ->orderby('views','desc')
                        ->paginate($this->per_page);

            return view('home',['articles'=>$articles]);
        }
        return view('error',['message'=>'user not logged in']);
    }
}
