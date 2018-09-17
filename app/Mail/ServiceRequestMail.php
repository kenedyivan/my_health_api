<?php

namespace App\Mail;

use App\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $service;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ServiceRequest $service)
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
        return $this->from('support.aar@gmail.com')
            ->subject('Service request '.'#'.$this->service->service_request_id)
            ->view('mail')->with([
                'customer_name' => $this->service->customer->first_name.' '.$this->service->customer->last_name,
                'phone_number' => $this->service->customer->phone_number,
                'service_type' => $this->service->service_type,
                'location' => $this->service->location,
                'date' => $this->service->set_date,
                'time' => $this->service->set_time,
            ]);
    }
}
