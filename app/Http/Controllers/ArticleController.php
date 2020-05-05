<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Catagory;
use App\Model\Article as ArticleModel;
use App\Model\ArticleCatagory;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class ArticleController  extends Controller
{
    use ImageUpload;

    public function index($id)
    {
        
        $article=ArticleModel::find($id);
        $article->timestamps=false;
        $article->increment('views');
        if($article){
            return view('post.full',['article'=>$article]);
        }
        return view('error');
    }
    /**
     * get add article form
     */
    public function getAddForm()
    {
        $catagory=Catagory::all();
        return view('post.add',['catagory'=>$catagory]);
    }
    /**
     * Edit an article
     */
    public function editForm($id)
    {
        $article=ArticleModel::find($id);
        if(is_null($article)){
            return view('error',['message'=>'invalid article']);
        }
        $catagory=Catagory::all();
        if(Gate::allows('update-post', $article)){
                return view('post.edit',['article'=>$article,'catagory'=>$catagory]);
        }else{
            return view('error',['message'=>'you are not autherorized']);
        }
    }

    public function edit(Request $request)
    {
        $this->validator($request);
        $article= ArticleModel::find($request->id);
        if(is_null($article)){
            return view('error',['message'=>'invalid article']);
        }
        $catagory=Catagory::all();
        if(Gate::allows('update-post', $article)){
            if( $request->image){
                try {
                $filePath = $this->UserImageUpload($request->image); //Passing $request->image as parameter to our created method
                $article->image_url = $filePath;
                } catch (Exception $e) {
                //Write your error message here
                return redirect()->back()->with('status','failed');
                }
            }
            $article->title = $request->title;
            $article->body = $request->body;
            $article->user_id_fk=Auth::id();
            if(count($request->catagory)>0){
                try{
                $article->catagories()->sync($request->catagory);
                }catch(Exception $e){}
            }  
            $article->save();
            return redirect()->back()->with('status','success');

        }else{
            return view('error',['message'=>'you are not autherorized']);
        }

    }

    public function delete($id)
    {
        $article= ArticleModel::find($id);
        if(Gate::allows('update-post', $article)){
            $article->catagories()->detach();
            $article->delete();
            return redirect()->back()->with('success','delete');
        }else{
            return view('error',['message'=>'you are not autherorized']);
        }
    }
    /**
     * validate inpu request 
     */
    protected function validator(Request $request)
    {
        return  $request->validate([
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'=> ['string','required'],
            'body' => ['string','required'],
        ]);
    }

    public function addPost(Request $request)
    {
        
        $this->validator($request);
        $data = new ArticleModel;     
 
        if( $request->image){
            try {
            $filePath = $this->UserImageUpload($request->image); //Passing $data->image as parameter to our created method
            $data->image_url = $filePath;
            } catch (Exception $e) {
            //Write your error message here
            return redirect()->back()->with('status','failed');
            }
        }
        $id=Auth::user()->id;
        $data->title = $request->title;
        $data->body = $request->body;
        $data->user_id_fk=$id;
        if($id != 0 ){
            $data->is_published=1;
        }
        $data->save();
        if(count($request->catagory)>0){
            $data->catagories()->attach($request->catagory);
        }  
        if($id==0){
            return redirect()->back()->with('status','pending for approval');
        }
        return redirect()->back()->with('status','success');
    }
}
