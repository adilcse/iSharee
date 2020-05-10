<?php

namespace App\Listeners;

use App\Events\NewCommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CommentAdded;
use Illuminate\Support\Facades\Mail;

class NotifyUser implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewCommentAdded  $event
     * @return void
     */
    public function handle(NewCommentAdded $event)
    {
        
        $user = $event->comment->article->user;
        if($user->is_email_verified){
            Mail::to($user->email)->send(new CommentAdded($event->comment));
        }
    }
}
