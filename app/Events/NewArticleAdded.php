<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Model\Article;

/**
 * Triggered when new articles added by any user
 * with details of new article. 
 * Admin will be notified with new article details.
 */
class NewArticleAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $article,$user;
    /**
     * Create a new event instance.
     * @param Article model
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article=$article;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
