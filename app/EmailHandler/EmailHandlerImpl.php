<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 9/18/18
 * Time: 10:45 AM
 */

namespace App\EmailHandler;


use App\EmailRespondent;
use App\Mail\EventCancelMail;
use App\Mail\EventMail;
use App\Mail\ForgotPasswordMail;
use App\Mail\ServiceRequestCancelMail;
use App\Mail\ServiceRequestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailHandlerImpl implements iEmailHandler
{

    private $respondents;

    /**
     * EmailHandlerImpl constructor.
     */
    public function __construct()
    {
        //$this->receipient = config('emailrecipient.email');
        $this->respondents = EmailRespondent::all();
    }

    public function sendServiceRequestEmail($service)
    {
        try {
            foreach ($this->respondents as $respondent) {
                Log::info("Sent service email to recipient handler address " . $this->respondents);
                Mail::to($respondent->email_address)->send(new ServiceRequestMail($service));
            }


        } catch (\Exception $e) {
            // Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendServiceRequestCancelEmail($service)
    {
        try {
            foreach ($this->respondents as $respondent) {
                Log::info("Sent cancel service " . $service->service_type
                    . " email to recipient handler address " . $this->respondents);
                Mail::to($respondent->email_address)->send(new ServiceRequestCancelMail($service));
            }


        } catch (\Exception $e) {
            // Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendAppointmentEmail($event)
    {
        try {
            foreach ($this->respondents as $respondent) {
                Log::info("Sent appointment email to recipient handler address " . $this->respondents);
                Mail::to($respondent->email_address)->send(new EventMail($event));
            }
        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }

    }

    public function sendCancelAppointmentEmail($event)
    {
        try {
            foreach ($this->respondents as $respondent) {
                Log::info("Sent cancel appointment '.$event->title.
            ' email to recipient handler address " . $this->respondents);
                Mail::to($respondent->email_address)->send(new EventCancelMail($event));
            }
        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendForgotPasswordEmail($userEmail, $temporaryPassword)
    {
        try {

            Log::info("Sent forgot password email, Email id = {$userEmail}");
            Mail::to($userEmail)->send(new ForgotPasswordMail($temporaryPassword));

        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }
    }
}