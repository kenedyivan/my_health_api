<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new message instance.
     *
     * @param $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('AAR, Reminder Created')
            ->view('reminder_confirmation_mail')->with([
                'title' => $this->event->title,
                'actual_date_time' => $this->event->actual_date_time,
                'location' => $this->event->location,
                'event_type' => $this->event->event_type->event_type,
                'customer_name' => $this->event->customer->first_name,
                'phone_number' => $this->event->customer->phone_number,
            ]);
    }
}
