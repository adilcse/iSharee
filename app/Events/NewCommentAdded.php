<?php
/**
 * Event for new Comment added by any user
 * PHP version: 7.0
 * 
 * @category Event
 * @package  App/Events
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Events/NewCommentAdded.php
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Model\Comments;

/**
 * When any user adds comment for any article 
 * this event will trigger
 * Recives comment model added by any user and send email to the author of article. 
 * 
 * @category Event
 * @package  App/Events
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Events/NewCommentAdded.php
 */
class NewCommentAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @param Comments $comment model for comment
     * 
     * @return void
     */
    public function __construct(Comments $comment)
    {
        $this->comment=$comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('newComment');
    }
}
