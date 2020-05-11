<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Comments;
use App\Model\Article;
use App\User;
use Gate;
use Auth;
use App\Events\NewCommentAdded;

/**
 * handles user comment related action
 */
class CommentController extends Controller
{
    /**
     * update comment status 
     * only authorized user can update thw comment
     */
    public function updateStatus(Request $request,$id)
    {
        $request->validate(['status'=>'boolean|required']);
        $comment=Comments::find($id);
        if(is_null($comment)){
            //return with custom mssage
            return response(['error'=>true,'message'=>'comment not exist'],405);
        }
        if(Gate::denies('update-post',$comment)){
            //unauthorized user returns with error message
            return response(['error'=>true,'message'=>'update not allowed'],405);
        }
        if('1' === $request->input('status')){
            //only admin can update comment status
            if(Auth::user()->is_admin){
                $comment->is_published=1;
                $comment->save();
            }
            return response(['error'=>true,'message'=>'you re not admin'],405);
        }else{
            //authorized user can delete a comment
            $comment->delete();
            if('article' === $request->input('from')){
                //retuen to view article page is comment is successfully deleted
                return redirect($request->url().'#viewComments')->with(['status'=>'comment deleted']);
            }
            return \response(['error'=>false,'message'=>'deleted'],200);
        }
        return \response(['error'=>false,'message'=>'success'],200);
    }

    /**
     * add comment for an article
     * @param request object
     */
    public function articleComment(Request $request)
    {
        //validate user's input with article id and comment
        $request->validate([
            'id'=>['required','string'],
            'comment'=>['required','string']
        ]);
        //find the article for which comment is added
        $article= Article::find($request->id);
        if(is_null($article)){
            return view('error')->with(['message'=>'invalid article']);
        }   
        if(Auth::check()){
            //only authorized user can comment
            if(Auth::id()==0){
                //guest user comment is set as unpublished
                $comment = new Comments(['article_id'=>$article->id,'is_published'=>0,'body'=>$request->comment,'user_id'=>Auth::id()]);
                $comment->save();
                //notify user of the article about new comment
                event(new NewCommentAdded($comment));
                // send pendin message
                return redirect()->back()->with(['comment'=>'pending for admin approval']);
            }else{
                //other user's comment is directly publihed
                $comment = new Comments(['article_id'=>$article->id,'body'=>$request->comment,'user_id'=>Auth::id()]);
                $comment->save();
                event(new NewCommentAdded($comment));
            }
            //return with success message
            return redirect()->back()->with(['status'=>'success']);
        }else{
            //return error if user is unauthorized
            return view('error',['message'=>'comment not allowed for unauthorized user']);
        }
    }
}
