<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Build comment mail to notify user anout the comment
 */
class CommentAdded extends Mailable
{
    use Queueable, SerializesModels;
    public $comment;
    
    /**
     * Create a new message instance.
     * 
     * @param Comment $comment model
     * 
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment=$comment;
    }

    /**
     * Build the message with mail comment view
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.commentAdded');
    }
}
