<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * generate mail layout to notify admin
 */
class ArticlePublished extends Mailable
{
    use Queueable, SerializesModels;
    public $article,$user;
    /**
     * Create a new message instance.
     * @param user model of the article
     * @param article model
     * @return void
     */
    public function __construct($user,$article)
    {
        $this->article=$article;
        $this->user=$user;
    }

    /**
     * Build the message with mail view.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.articlePublished');
    }
}
