<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Comments;
use App\User;
use Gate;
class CommentController extends Controller
{
    public function updateStatus(Request $request,$id)
    {
        $request->validate(['status'=>'boolean|required']);
        $comment=Comments::find($id);
        if(is_null($comment)){
            return response(['error'=>true,'message'=>'comment not exist'],405);
        }
        if(Gate::denies('update-post',$comment)){
            return response(['error'=>true,'message'=>'update not allowed'],405);
        }
        if('1' === $request->input('status')){
            $comment->is_published=1;
            $comment->save();
        }else{
            $comment->delete();
            if('article' === $request->input('from')){
                return redirect($request->url().'#viewComments')->with(['status'=>'comment deleted']);
            }
            return \response(['error'=>false,'message'=>'deleted'],200);
        }
        return \response(['error'=>false,'message'=>'success'],200);
    }
}
