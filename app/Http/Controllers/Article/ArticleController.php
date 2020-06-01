<?php
/**
 * Handles Article related actions
 * PHP version: 7.0
 * 
 * @category Controller/Article
 * @package  Http/Controller/Article
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Article/ArticleController.php
 */

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catagory;
use App\Model\Article as ArticleModel;
use App\Model\ArticleCatagory;
use App\Traits\ImageUpload;
use App\Traits\ImageDelete;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Events\NewArticleAdded;
use App\Helper\Slug;
use App\Helper\Constants;
use Redirect;

/**
 * Handles all article related actions
 * 
 * @category Controller/Article
 * @package  Http/Controller/Article
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Article/ArticleController.php
 */
class ArticleController  extends Controller
{
    //to upload image to the server
    use ImageUpload;
    use ImageDelete;
    /**
     * View fullscreen article 
     * 
     * @param string $slug name of the article to viewed
     * 
     * @return view of full page article
     */
    public function index($slug)
    {
        //finds article by its slug name
        $article=ArticleModel::where('slug', $slug)->first();
        if ($article) {
            //increment number of views of thr article
            $article->timestamps=false;
            $article->increment('views');
            if ($article->is_published) {
                //normal user can view article if it is published
                return view('post.full', ['article'=>$article]);
            } else if (Gate::allows('update-post', $article)) {
                //athorized user can view article even if it is not published
                return view(
                    'post.full', 
                    ['article'=>$article,'status'=>'unpublished']
                );
            } else {
                //return to error page with error mesaage
                return view(
                    'error', 
                    ['message'=>Constants::$MESSAGE_ARTICLE_NOT_PUBLISHED]
                );
            }
        }
        return view('error', ['message'=>Constants::$ERROR_INVALID_ARTICLE_ID]);
    }
    /**
     * Get add article form for adding new article
     * 
     * @return view add post view
     */
    public function getAddForm()
    {
        //gets all catagory list
        $catagory=Catagory::all();
        return view('post.add', ['catagory'=>$catagory]);
    }
    /**
     * Gets edit article form for editing the article
     * 
     * @param string $slug for the article to be edited
     * 
     * @return view edit page
     */
    public function editForm($slug)
    {
        $article=ArticleModel::where('slug', $slug)->first();
        if (is_null($article)) {
            return view('error', ['message'=>Constants::$ERROR_INVALID_ARTICLE_ID]);
        }
        $catagory=Catagory::all();
        if (Gate::allows('update-post', $article)) {
                return view(
                    'post.edit', 
                    ['article'=>$article,'catagory'=>$catagory]
                );
        } else {
            return view(
                'error',
                ['message'=>'you are not autherorized']
            );
        }
    }

    /**
     * Update article when user submits edit form
     * 
     * @param Request $request object
     * 
     * @return redirect with success message
     */
    public function edit(Request $request)
    {
        $this->validator($request);
        $article= ArticleModel::find($request->id);
        if (is_null($article)) {
            return view('error', ['message'=>'invalid article']);
        }
        $catagory=Catagory::all();
        if (Gate::allows('update-post', $article)) {
            //only aithorized user can edit the post
            if ($request->image) {
                try {
                    $filePath = $this->userImageUpload($request->image, $article->id); //Passing $request->image as parameter to our created method
                    $article->image_url = $filePath;
                } catch (Exception $e) {
                    //Write your error message here
                    return redirect()
                        ->back()
                        ->with('status', Constants::$ERROR_FAILED);
                }
            }
            //update article details
            $article->title = $request->title;
            $article->slug = Slug::createSlug('article', $request->title);
            $article->body = $request->body;
            $article->allow_image_as_slider = $request->sliderCheck?1:0;
            if (\is_array($request->catagory) && count($request->catagory) > 0) {
                try{
                    $article->catagories()->sync($request->catagory);
                }catch(Exception $e){
                    return view(
                        'error', 
                        ['message'=>Constants::$ERROR_CREATING_CATAGORY]
                    );
                }
            }  
            $article->save();
            //redirect to te article page when update is successfull
            return redirect(route('article', $article->slug))
                ->with('status', Constants::$SUCCESS_MSG);
        } else {
            //redirect to error page with custom message
            return view('error', ['message'=>Constants::$ERROR_UNAUTHORIZED]);
        }
    }

    /**
     * Delete article when user deletes the article
     * deleting an article will also delete all likes and comments
     * 
     * @param string $slug name of the article to be deleted
     * 
     * @return redirect with confirmation
     */
    public function delete($slug)
    {
        $article= ArticleModel::where('slug', $slug)->first();
        if (Gate::allows('update-post', $article)) {
            //only authorized user can dlete the article
            //remove all likes,comment and catagory before deleting the article
            if ($article->image_url) {
                $this->userImageDelete($article->image_url);
            }
            if ($article->payment) {
                $article->payment->delete();
            }
            $article->likes()->detach();
            $article->comments()->detach();
            $article->catagories()->detach();            
            $article->delete();
            return redirect(route('home'))->with('success', Constants::$SUCCESS_DELETE);
        } else {
            return view('error', ['message'=>Constants::$ERROR_UNAUTHORIZED]);
        }
    }

    /**
     * Like or dislike an article by its id
     * any registered user can like an article
     * a single user can only like once an article
     * guest user can't like any article
     * 
     * @param int $id of the article to be liked
     * 
     * @return response with like status
     */
    public function like($id)
    {
        if (!Auth::check() || !Auth::id()) {
            //guest user can't like a post
            return ['error'=>true,'message'=>Constants::$MESSAGE_LOGIN_FIRST];
        }
        $article=ArticleModel::find($id);
        if (is_null($article)) {
            //invalid article if id doesn't exist
            return ['error'=>true,'message'=>Constants::$ERROR_INVALID_ARTICLE_ID];  
        }
        $iliked=$article->likes()->where('user_id', Auth::id())->get();
        //check user liked a post or not
        if (count($iliked)>0) {
            $article->likes()->detach(Auth::id());
            return ['error'=>false,'liked'=>false];
        }
        try{
            //like post if user didn't liked already
            $article->likes()->attach(Auth::id(), ['react'=>'LIKE']);
            return ['error'=>false,'liked'=>true];
        }catch(Exception $e){
            //invalid response if already liked
            return ['error'=>true, 'message'=>Constants::$MESSAGE_LIKED];
        }
    }

    /**
     * Validate input request 
     * 
     * @param Request $request object
     * 
     * @return Request after validation 
     */
    protected function validator(Request $request)
    {
        return  $request->validate(
            [
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'=> ['string','required'],
            'body' => ['string','required'],
            ]
        );
    }

    /**
     * Add a new article by a user
     * 
     * @param Request $request object with all details
     * 
     * @return response add status
     */
    public function addPost(Request $request)
    {
        $this->validator($request);
        //create a new article model
        $data = new ArticleModel;     
        if ($request->image) {
            //upload image 
            try {
                $filePath = $this->userImageUpload($request->image, $data->id); //Passing $data->image as parameter to our created method
                $data->image_url = $filePath;
            } catch (Exception $e) {
                //Write your error message here
                return redirect()->back()->with('status', Constants::$ERROR_FAILED);
            }
        }
        //update article data
        $id=Auth::id();
        $data->title = $request->title;
        $data->body = $request->body;
        $data->user_id=$id;
        if (!$request->sliderCheck) {
            $data->allow_image_as_slider = 0;
        }
        try{
            //create slug by article name
            $data->slug= Slug::createSlug('article', $request->title);
            $data->save();
            if ($request->catagory && count($request->catagory) > 0) {
                $data->catagories()->attach($request->catagory);
            }
            return redirect()   
                ->route('articlePaymentPage')
                ->with(
                    'data',
                    [
                        'userId' => $id, 
                        'email' => Auth::user()->email,
                        'title'=>$data->title,
                        'slug'=>$data->slug,
                        'articleId'=>$data->id
                    ]
                );
        }
        catch(Exception $e){
            //throw exception
            return redirect()
                ->back()
                ->withError(['status'=>Constants::$ERROR_SLUG_CREATE]);
        }
        //return to article display page is article created successfully
        return redirect(route('article', $data->slug))
            ->with('create', Constants::$SUCCESS_MSG);
    }
}
