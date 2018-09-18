<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 9/18/18
 * Time: 10:45 AM
 */

namespace App\EmailHandler;


use App\Mail\EventMail;
use App\Mail\ServiceRequestMail;
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
            Mail::to($this->receipient)->send(new ServiceRequestMail($service));

        } catch (\Exception $e) {
            // Error sending mail
        }
    }

    public function sendAppointmentEmail($event)
    {
        try {
            Mail::to($this->receipient)->send(new EventMail($event));
        } catch (\Exception $e) {
            //Error sending mail
        }

    }
}