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
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $articles=Article::where('articles.is_published',1)
                            ->orderby('views','desc')
                            ->paginate();
        return view('home',['articles'=>$articles]);
    }
}
