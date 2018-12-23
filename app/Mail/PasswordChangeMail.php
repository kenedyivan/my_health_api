<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    private $subjectTitle;
    private $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->subjectTitle = 'Password Changed';
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectTitle)
            ->view('password_updated_mail')->with('user', $this->user);
    }
}
