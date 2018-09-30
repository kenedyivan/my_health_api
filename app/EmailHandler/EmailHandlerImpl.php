<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 9/18/18
 * Time: 10:45 AM
 */

namespace App\EmailHandler;


use App\Mail\EventCancelMail;
use App\Mail\EventMail;
use App\Mail\ServiceRequestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailHandlerImpl implements iEmailHandler
{

    private $receipient;

    /**
     * EmailHandlerImpl constructor.
     */
    public function __construct()
    {
        $this->receipient = config('emailrecipient.email');
    }

    public function sendServiceRequestEmail($service)
    {
        try {
            Log::info("Sent service email to recipient address " . $this->receipient);
            Mail::to($this->receipient)->send(new ServiceRequestMail($service));

        } catch (\Exception $e) {
            // Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendServiceRequestCancelEmail($service)
    {
        // TODO: Implement sendServiceRequestCancelEmail() method.
    }

    public function sendAppointmentEmail($event)
    {
        try {
            Log::info("Sent appointment email to recipient address " . $this->receipient);
            Mail::to($this->receipient)->send(new EventMail($event));
        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }

    }

    public function sendCancelAppointmentEmail($event)
    {
        try {
            Log::info("Sent cancel appointment '.$event->title.
            ' email to recipient address " . $this->receipient);
            Mail::to($this->receipient)->send(new EventCancelMail($event));
        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }
    }
}