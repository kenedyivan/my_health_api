<?php

namespace App\Mail;

use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventCancelMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event)
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
        $event_title = '';
        if ($this->event->event_type_id == 1) {
            $event_title = 'Cancelled Appointment ' . '#' . $this->event->id;
        } else if ($this->event->event_type_id == 2) {
            $event_title = 'Cancelled Clinic visit ' . '#' . $this->event->id;
        }
        return $this->subject($event_title)
            ->view('cancel_event_mail')->with([
                'title' => $this->event->title,
                'actual_date_time' => $this->event->actual_date_time,
                'location' => $this->event->location,
                'event_type' => $this->event->event_type->event_type,
                'customer_name' => $this->event->customer->first_name . ' ' . $this->event->customer->last_name,
                'phone_number' => $this->event->customer->phone_number,
            ]);
    }
}
