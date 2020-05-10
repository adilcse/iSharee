<?php

namespace App\Listeners;

use App\Events\NewArticleAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ArticlePublished;
use Illuminate\Support\Facades\Mail;
use App\User;

class NotifyAdmin implements ShouldQueue
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
     * @param  NewArticleAdded  $event
     * @return void
     */
    public function handle(NewArticleAdded $event)
    {
        $admins = User::where('is_admin',1)
                    ->where('is_active',1)
                    ->where('is_email_verified',1)
                    ->get();
        foreach($admins as $admin){
            Mail::to($admin->email)->send(new ArticlePublished($event->article->user,$event->article));
        }
    }
}
