<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $subjectTitle;
    private $temporaryPassword;

    /**
     * Create a new message instance.
     *
     * @param $temporaryPassword
     */
    public function __construct($temporaryPassword)
    {
        $this->subjectTitle = "Reset your password on AAR My Health";
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject($this->subjectTitle)
            ->view('forgot_password_mail')->with('temporaryPassword', $this->temporaryPassword);
    }
}
