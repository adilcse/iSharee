<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Article;
use App\Traits\ImageDelete;
use App\Traits\ImageUpload;
/**
 * handles article related actions for admin 
 */
class AdminArticleController extends Controller
{
    use ImageUpload;
    use ImageDelete;
    /**
     * update article status and return status of article
     * only admin can update articles
     * @param Request
     * @param id of article whose status is to be updated
     * @return Response
     */
    public function articleUpdate(Request $request,$id)
    {
        try{
            $article=Article::find($id);
            if(is_null($article)){
                return response(['status'=>false,'message'=>'invalid article id'],404);    
            }
            $ip=intval($request->input('status'));
            if(1 === $ip || 0 === $ip){
                //change article status if valid status given
                $article->is_published = $ip;
                $article->save();
                return response(['status'=>true,'message'=>'updated'],200);     
            }
            //return with false status if request fails
            return response(['status'=>false,'message'=>'invalid status update'],200);
        }catch(Exception $e){
            return response(['status'=>false,'message'=>'something went wrong'],400);
        }
    }

    /**
     * delete an article with given id
     * only admin can delete an article
     * @param Request object
     * @param id of article to be deleted
     * @return Response
     */
    public function articleDelete(Request $request,$id)
    {
        try{
            $article=Article::find($id);
            if(is_null($article)){
                return view('error',['message'=>'invalid article ']);
            }
            //remove all likes comments and catgory of an article and delete it
            $status=$this->UserImageDelete($article->image_url);
            $article->catagories()->detach();
            $article->comments()->detach();
            $article->likes()->detach();
            $article->delete();
            //return success if everithing deleted completely
            return redirect()->back()->with(['success'=>'deleted successfully']);
        }
        catch(Exception $e){
            return view('error',['message'=>'can not delete the Article']);
        }
        
    }
}
