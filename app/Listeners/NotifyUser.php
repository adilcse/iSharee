<?php

namespace App\Listeners;

use App\Events\NewCommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CommentAdded;
use Illuminate\Support\Facades\Mail;

/**
 * Listen to any comment added in an article and notify user about that comment
 */
class NotifyUser implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  NewCommentAdded  $event
     * @return void
     */
    public function handle(NewCommentAdded $event)
    {
        $user = $event->comment->article->user;
        //send email to the user to notify about comment
        if($user->is_email_verified && $event->comment->is_published){
            Mail::to($user->email)->queue(new CommentAdded($event->comment));
        }
    }
}
