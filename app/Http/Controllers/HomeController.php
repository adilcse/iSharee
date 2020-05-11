<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Catagory;
use App\Model\ArticleCatagory;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *set per page items in home page from env
     * @return void
     */
    protected $per_page;

    public function __construct()
    {
        $this->per_page=intval(env('ITEMS_PER_PAGE',5));
    }

    /**
     * Show the application home page.
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
    
    /**
     * catagory page.
     * Display article belongs to a given catagory
     * @param slum name of the catagory to be displayed
     * @return home view with artcles belongs to a catagory
     */
    public function catagory($id)
    {
        try{
            //find the catagory
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

    /**
     * display all artice published by a user
     * @param request oblect
     */
    public function myArticle(Request $request)
    {
        //find user id and fetch all article of the user
        $userId=$request->user()->id;
        if(!is_null($userId)){
            $articles=Article::where('user_id',$userId)
                        ->orderby('views','desc')
                        ->paginate($this->per_page);
            //return home view with fetched articles
            return view('home',['articles'=>$articles,'myArticle'=>true]);
        }
        return view('error',['message'=>'user not logged in']);
    }

    /**
     * dispay articles of any perticlular user
     * @param requst object
     * @param id of the user whose article are to be displayed
     * @return home view with user's articles
     */
    public function userArticles(Request $request,$id)
    {
        $user=User::find($id);
        if(is_null($user)){
            return redirect()->back()->withErrors(['user does not exist']);
        }
        //admin can view all published articles of the user 
        if(Auth::user()->is_admin){
            $articles=$user->articles()->paginate($this->per_page);
        }else{
            //otheruser can only view published articles
            $articles=$user->articles()
                        ->where('is_published',1)
                        ->paginate($this->per_page);
        }
        if($articles->items() < 1){
            return view('home',['articles'=>null]);
        }
        return view('home',['articles'=>$articles,'name'=>$user->name]);
    }

    /**
     * iser can update name of his/her profile 
     * @param request object
     * @return redirect with proper message
     */
    public function userUpdate(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:50|min:3',
            'id'=>'numeric|required',
            'newPassword'=>'nullable|string|min:6',
            'cPassword'=> 'nullable|string|min:6',
            'oldPassword'=> 'nullable|string|min:6'
        ]);
        //fetch the user update name and return with response
        try{
            $user=Auth::user();
            $user->name=$request->input('name');
            if($request->changePasswordCheck){
                if($request->cPassword !== $request->newPassword){
                    return redirect()->back()->withErrors(['password'=>'confirm password does not matched']);
                }else if(Auth::user()->oauth_token){
                    $user->password = Hash::make($request->newPassword);
                    $user->oauth_token = null;
                }else{
                    if(Hash::check($request->oldPassword,Auth::user()->password)){
                        $user->password = Hash::make($request->newPassword);
                        $user->oauth_token = null;
                    }else{
                        return redirect()->back()->withErrors(['password'=>'invalid password']);
                    }
                }
            }
            $user->save();
            return redirect()->back()->with(['status'=>'success']);
        }
        catch(Exception $e){
            return view('error',['message'=>'can not update name']);
        }
        
    }
}
