<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Catagory;
use App\Model\Article;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AddArticle extends Controller
{
    use ImageUpload;

    public function index()
    {
        return view('post');
    }
    public function getAddForm()
    {
        $catagory=Catagory::all();
        return view('post.add',['catagory'=>$catagory]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'image' =>  'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'=> 'string|required',
            'body' => 'string|required'
        ]);
    }

    public function addPost(Request $request)
    {
        
        $request->validate([
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'=> ['string','required'],
            'body' => ['string','required'],
        
        ]);
        $data = new Article;        
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
        if(Auth::guard('admin')->check()){
            $id=Auth::guard('admin')->user()->id;
        }

        $data->title = $request->title;
        $data->body = $request->body;
        $data->catagory_id_fk=$request->catagory;
        $data->user_id_fk=$id;
        if(Auth::guard('admin')->check()){
            $data->is_published=1;
        }
        $data->save();
        if($id==0){
            return redirect()->back()->with('status','pending for approval');
        }
        return redirect()->back()->with('status','success');
    }
}
