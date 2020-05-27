<?php
/**
 * Event for new article added by any user
 * PHP version: 7.0
 * 
 * @category Event
 * @package  App/Events
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Events/NewArticleAdded.php
 */
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
 * 
 * @category Event
 * @package  App/Events
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Events/NewArticleAdded.php
 */
class NewArticleAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $article,$user;
    /**
     * Create a new event instance.
     * 
     * @param Article $article model of article
     * 
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
        return new PrivateChannel('newArticle');
    }
}
