<?php

/**
 * HandlesComment related actions
 * PHP version: 7.0
 *
 * @category Controller/Article
 * @package  Http/Controller/Article
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Article/CommentController.php
 */

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Comments;
use App\Model\Article;
use App\User;
use Gate;
use Auth;
use App\Events\NewCommentAdded;
use App\Helper\Constants;

/**
 * Handles user comment related action
 *
 * @category Controller/Article
 * @package  Http/Controller/Article
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Article/CommentController.php
 */
class CommentController extends Controller
{
    /**
     * Update comment status
     * only authorized user can update thw comment
     *
     * @param Request $request http request object
     * @param int     $id      comment it to be update
     *
     * @return response with update status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'boolean|required']);
        $comment = Comments::find($id);
        if (is_null($comment)) {
            //return with custom mssage
            return response(['error' => true, 'message' => Constants::$ERROR_ID], 405);
        }
        if (Gate::denies('update-post', $comment)) {
            //unauthorized user returns with error message
            return response(
                ['error' => true, 'message' => Constants::$ERROR_UNAUTHORIZED],
                405
            );
        }
        if ('1' === $request->input('status')) {
            //only admin can update comment status
            if (Auth::user()->is_admin) {
                $comment->is_published = 1;
                $comment->save();
                // event(new NewCommentAdded($comment));
                return response(
                    ['error' => false, 'message' => Constants::$SUCCESS_DELETE],
                    200
                );
            }
            return response(
                ['error' => true, 'message' => Constants::$ERROR_UNAUTHORIZED],
                405
            );
        } else {
            //authorized user can delete a comment
            $comment->delete();
            if ('article' === $request->input('from')) {
                //retuen to view article page is comment is successfully deleted
                return redirect($request->url() . '#viewComments')
                    ->with(['status' => Constants::$SUCCESS_DELETE]);
            }
            return response(
                ['error' => false, 'message' => Constants::$SUCCESS_DELETE],
                200
            );
        }
        return response(['error' => false, 'message' => Constants::$SUCCESS_DELETE], 200);
    }

    /**
     * Add comment for an article
     *
     * @param Request $request object
     *
     * @return view with comment
     */
    public function articleComment(Request $request)
    {
        //validate user's input with article id and comment
        $request->validate(
            [
                'id' => ['required', 'string'],
                'comment' => ['required', 'string']
            ]
        );
        //find the article for which comment is added
        $article = Article::find($request->id);
        if (is_null($article)) {
            return view('error')
                ->with(['message' => Constants::$ERROR_INVALID_ARTICLE_ID]);
        }
        if (Auth::check()) {
            //only authorized user can comment
            if (Auth::id() == 0) {
                //guest user comment is set as unpublished
                $comment = new Comments(
                    [
                        'article_id' => $article->id,
                        'is_published' => 0,
                        'body' => $request->comment,
                        'user_id' => Auth::id()
                    ]
                );
                $comment->save();
                // send pending message
                return redirect()
                    ->back()
                    ->with(['comment' => Constants::$MESSAGE_APPROVAL_PENDING]);
            } else {
                //other user's comment is directly publihed
                $comment = new Comments(
                    [
                        'article_id' => $article->id,
                        'body' => $request->comment,
                        'user_id' => Auth::id()
                    ]
                );
                $comment->save();
                //  event(new NewCommentAdded($comment));
            }
            //return with success message
            return redirect()->back()->with(['status' => 'success']);
        } else {
            //return error if user is unauthorized
            return view('error', ['message' => Constants::$ERROR_UNAUTHORIZED]);
        }
    }
}
