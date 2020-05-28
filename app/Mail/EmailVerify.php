<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;
    
    public $otp;
    /**
     * Create a new message instance.
     * 
     * @param int $otp for email verification
     * 
     * @return void
     */
    public function __construct($otp)
    {
        $this->otp=$otp;
    }

    /**
     * Build the message to verify email address.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.email.verify');
    }
}
