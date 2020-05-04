<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Catagory;
class AddArticle extends Controller
{
    public function index()
    {
        return view('post');
    }
    public function getAddForm()
    {
        $catagory=Catagory::all();
        return view('post.add',['catagory'=>$catagory]);
    }
    public function addPost(Request $request)
    {
        
    }
}
