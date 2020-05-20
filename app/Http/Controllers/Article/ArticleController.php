<?php

namespace App\Http\Controllers\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catagory;
use App\Model\Article as ArticleModel;
use App\Model\ArticleCatagory;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Events\NewArticleAdded;
use App\Helper\Slug;
use Redirect;
/**
 * handles all article related actions
 */
class ArticleController  extends Controller
{
    //to upload image to the server
    use ImageUpload;

    /**
     * view fullscreen article 
     * @param slug name of the article to viewed
     * @return view of full page article
     */
    public function index($id)
    {
        //finds article by its slug name
        $article=ArticleModel::where('slug',$id)->first();
        if($article){
            //increment number of views of thr article
            $article->timestamps=false;
            $article->increment('views');
            if($article->is_published){
                //normal user can view article if it is published
                return view('post.full',['article'=>$article]);
            }else if(Gate::allows('update-post', $article)){
                //athorized user can view article even if it is not published
                return view('post.full',['article'=>$article,'status'=>'unpublished']);
            }else{
                //return to error page with error mesaage
                return view('error',['message'=>'article is not published yet']);
            }
        }
        return view('error',['message'=>'article does not exist']);
    }
    /**
     * get add article form for adding new article
     */
    public function getAddForm()
    {
        //gets all catagory list
        $catagory=Catagory::all();
        return view('post.add',['catagory'=>$catagory]);
    }
    /**
     * Gets edit article form for editing the article
     * @param slug for the article to be edited
     * @return view edit page
     */
    public function editForm($id)
    {
        $article=ArticleModel::where('slug',$id)->first();
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

    /**
     * update article when user submits edit form
     * @param request object
     * @return redirect with success message
     */
    public function edit(Request $request)
    {
        $this->validator($request);
        $article= ArticleModel::find($request->id);
        if(is_null($article)){
            return view('error',['message'=>'invalid article']);
        }
        $catagory=Catagory::all();
        if(Gate::allows('update-post', $article)){
            //only aithorized user can edit the post
            if( $request->image){
                try {
                $filePath = $this->UserImageUpload($request->image,$article->id); //Passing $request->image as parameter to our created method
                $article->image_url = $filePath;
                } catch (Exception $e) {
                //Write your error message here
                return redirect()->back()->with('status','failed');
                }
            }
            //update article details
            $article->title = $request->title;
            $article->slug = Slug::createSlug('article',$request->title);
            $article->body = $request->body;
            $article->allow_image_as_slider = $request->sliderCheck?1:0;
            if(count($request->catagory)>0){
                try{
                $article->catagories()->sync($request->catagory);
                }catch(Exception $e){
                    return view('error',['message'=>'error in adding catagory']);
                }
            }  
            $article->save();
            //redirect to te article page when update is successfull
            return redirect(route('article',$article->slug))->with('status','success');
        }else{
            //redirect to error page with custom message
            return view('error',['message'=>'you are not autherorized']);
        }
    }

    /**
     * delete article when user deletes the article
     * deleting an article will also delete all likes and comments
     * @param slug name of the article to be deleted
     * @return redirect with confirmation
     */
    public function delete($id)
    {

        $article= ArticleModel::where('slug',$id)->first();
        if(Gate::allows('update-post', $article)){
            //only authorized user can dlete the article
            //remove all likes,comment and catagory before deleting the article
            $article->likes()->detach();
            $article->comments()->detach();
            $article->catagories()->detach();
            $article->delete();
            return redirect()->back()->with('success','delete');
        }else{
            return view('error',['message'=>'you are not autherorized']);
        }
    }
/**
 * like or dislike an article by its id
 * any registered user can like an article
 * a single user can only like once an article
 * guest user can't like any article
 * @param id of the article to be liked
 * @return response with like status
 */
    public function like($id)
    {
        if(!Auth::check() || Auth::id() === 0){
            //guest user can't like a post
            return ['error'=>true,'message'=>'please login first'];
        }
        $article=ArticleModel::find($id);
        if(is_null($article)){
            //invalid article if id doesn't exist
            return ['error'=>true,'message'=>'invalid article id'];  
        }
        $iliked=$article->likes()->where('user_id',Auth::id())->get();
        //check user liked a post or not
        if(count($iliked)>0){
            $article->likes()->detach(Auth::id());
            return ['error'=>false,'liked'=>false];
        }
        try{
            //like post if user didn't liked already
            $article->likes()->attach(Auth::id(),['react'=>'LIKE']);
            return ['error'=>false,'liked'=>true];
        }catch(Exception $e){
            //invalid response if already liked
            return ['error'=>true,'message'=>'already liked'];
        }
    }

    /**
     * validate input request 
     * @param request object
     * @return request after validation 
     */
    protected function validator(Request $request)
    {
        return  $request->validate([
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'=> ['string','required'],
            'body' => ['string','required'],
        ]);
    }
    /**
     * add a new article by a user
     * @param requst object with all details
     * @return response add status
     */
    public function addPost(Request $request)
    {
        $this->validator($request);
        //create a new article model
        $data = new ArticleModel;     
        if( $request->image){
            //upload image 
            try {
            $filePath = $this->UserImageUpload($request->image,$data->id); //Passing $data->image as parameter to our created method
            $data->image_url = $filePath;
            } catch (Exception $e) {
            //Write your error message here
            return redirect()->back()->with('status','failed');
            }
        }
        //update article data
        $id=Auth::id();
        $data->title = $request->title;
        $data->body = $request->body;
        $data->user_id=$id;
        if(!$request->sliderCheck){
            $data->allow_image_as_slider = 0;
        }
        try{
            //create slug by article name
            $data->slug= Slug::createSlug('article',$request->title);
            $data->save();
            if($request->catagory && count($request->catagory)>0){
                $data->catagories()->attach($request->catagory);
            }
            return redirect()->route('articlePaymentPage')->with('data',['userId' => $id, 
                                                'email' => Auth::user()->email,
                                                'title'=>$data->title,
                                                'slug'=>$data->slug,
                                                'articleId'=>$data->id]);
        }
        catch(Exception $e){
            //throw exception
            return redirect()->back()->withError(['status'=>'can not create slug']);
        }
        //return to article display page is article created successfully
        return redirect(route('article',$data->slug))->with('create','success');
    }
}
