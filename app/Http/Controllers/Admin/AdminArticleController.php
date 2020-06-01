<?php
/**
 * Handles article actions of admin
 * PHP version: 7.0
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/AdminArticleController.php
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Article;
use App\Traits\ImageDelete;
use App\Traits\ImageUpload;
use App\Helper\Constants;
/**
 * Controller for admin article related action
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/AdminArticleController.php
 */
class AdminArticleController extends Controller
{
    use ImageUpload;
    use ImageDelete;
    /**
     * Update article status and return status of article
     * only admin can update articles
     * 
     * @param Request $request of user
     * @param int     $id      of article whose status is to be updated
     * 
     * @return json response with update status
     */
    public function articleUpdate(Request $request,$id)
    {
        try{
            $article=Article::find($id);
            if (is_null($article)) {
                return response(
                    ['status'=>false, 
                    'message'=>Constants::$ERROR_INVALID_ARTICLE_ID], 
                    404
                );    
            }
            $isPublished=intval($request->input('status'));
            if (1 === $isPublished || 0 === $isPublished) {
                //change article status if valid status given
                $article->is_published = $isPublished;
                $article->save();
                return response(
                    ['status'=>true,'message'=>Constants::$SUCCESS_UPDATED], 
                    200
                );     
            }
            //return with false status if request fails
            return response(
                ['status'=>false,
                'message'=>Constants::$ERROR_STATUS_UPDATE], 
                200
            );
        }catch(Exception $e){
            return response(
                ['status'=>false,
                'message'=>Constants::$ERROR_WRONG], 
                400
            );
        }
    }

    /**
     * Delete an article with given id
     * only admin can delete an article
     * 
     * @param Request $request of an user
     * @param int     $id      of article to be deleted
     * 
     * @return Response redirect success|error page
     */
    public function articleDelete(Request $request,$id)
    {
        try{
            $article=Article::find($id);
            if (is_null($article)) {
                return view(
                    'error', 
                    ['message'=>Constants::$ERROR_INVALID_ARTICLE_ID]
                );
            }
            //remove all likes comments and catgory of an article and delete it
            $status=$this->userImageDelete($article->image_url);
            $article->catagories()->detach();
            $article->comments()->detach();
            $article->likes()->detach();
            $article->delete();
            //return success if everithing deleted completely
            return redirect()->back()->with(['success'=>Constants::$SUCCESS_DELETE]);
        }
        catch(Exception $e){
            return view('error', ['message'=>Constant::$ERROR_ARTICLE_DELETE]);
        }
        
    }
}
