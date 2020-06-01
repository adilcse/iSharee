<?php

namespace App\Listeners;

use App\Events\NewArticleAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ArticlePublished;
use Illuminate\Support\Facades\Mail;
use App\Model\User;

/**
 * Listen to add new article event and notify admin that new article is added
 */
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
     * @param NewArticleAdded $event to be handled
     * 
     * @return void
     */
    public function handle(NewArticleAdded $event)
    {
        //find all admin users and send mail to notify about new artice
        $admins = User::where('is_admin', 1)
                    ->where('is_active', 1)
                    ->where('is_email_verified', 1)
                    ->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->queue(new ArticlePublished($event->article->user, $event->article));
        }
    }
}
