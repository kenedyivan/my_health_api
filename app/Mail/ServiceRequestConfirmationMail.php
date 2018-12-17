<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceRequestConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $service;

    /**
     * Create a new message instance.
     *
     * @param $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('AAR '.$this->service->service_type . ' Service Request')
            ->view('service_request_confirmation_mail')->with([
                'customer_name' => $this->service->customer->first_name,
                'phone_number' => $this->service->customer->phone_number,
                'service_type' => $this->service->service_type,
                'location' => $this->service->location,
                'date' => $this->service->set_date,
                'time' => $this->service->set_time,
            ]);
    }
}
