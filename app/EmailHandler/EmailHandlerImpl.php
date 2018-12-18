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
use App\Mail\ReminderMail;
use App\Mail\ForgotPasswordMail;
use App\Mail\ReminderConfirmationMail;
use App\Mail\ServiceRequestCancelMail;
use App\Mail\ServiceRequestConfirmationMail;
use App\Mail\ServiceRequestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailHandlerImpl implements iEmailHandler
{

    private $reminderRespondents;
    private $serviceRequestRespondents;

    /**
     * EmailHandlerImpl constructor.
     */
    public function __construct()
    {
        $this->reminderRespondents = EmailRespondent::where('reminder', 1)->get();
        $this->serviceRequestRespondents = EmailRespondent::where('service_request', 1)->get();
    }

    public function sendServiceRequestEmail($service)
    {
        try {
            foreach ($this->serviceRequestRespondents as $respondent) {
                Log::info("Sent service email to respondent, Email address = " . $respondent->email_address);
                Mail::to($respondent->email_address)->send(new ServiceRequestMail($service));
            }


        } catch (\Exception $e) {
            // Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendServiceRequestConfirmationEmail($service)
    {
        try {
            Log::info("Sent service email received confirmation  to user, Email address = "
                . $service->customer->email_address);
            Mail::to($service->customer->email_address)->send(new ServiceRequestConfirmationMail($service));


        } catch (\Exception $e) {
            // Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendServiceRequestCancelEmail($service)
    {
        try {
            foreach ($this->serviceRequestRespondents as $respondent) {
                Log::info("Sent cancel service " . $service->service_type
                    . " email to respondent, Email address = " . $respondent->email_address);
                Mail::to($respondent->email_address)->send(new ServiceRequestCancelMail($service));
            }


        } catch (\Exception $e) {
            // Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendReminderEmail($event)
    {
        try {
            foreach ($this->reminderRespondents as $respondent) {
                Log::info("Sent reminder email to respondent, Email address = " . $respondent->email_address);
                Mail::to($respondent->email_address)->send(new ReminderMail($event));
            }
        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }

    }

    public function sendReminderConfirmationEmail($event)
    {
        try {

            Log::info("Sent reminder email to uer, Email address = " . $event->customer->email_address);
            Mail::to($event->customer->email_address)->send(new ReminderConfirmationMail($event));

        } catch (\Exception $e) {
            //Error sending mail
            Log::debug($e->getMessage());
        }
    }

    public function sendCancelReminderEmail($event)
    {
        try {
            foreach ($this->reminderRespondents as $respondent) {
                Log::info("Sent cancel reminder '.$event->title.
            ' email to recipient handler address " . $respondent->email_address);
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