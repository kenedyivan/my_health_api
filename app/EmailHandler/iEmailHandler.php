<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 9/18/18
 * Time: 10:44 AM
 */

namespace App\EmailHandler;


interface iEmailHandler
{
    public function sendServiceRequestEmail($service);
    public function sendServiceRequestConfirmationEmail($service);
    public function sendServiceRequestCancelEmail($service);
    public function sendReminderEmail($event);
    public function sendReminderConfirmationEmail($event);
    public function sendCancelReminderEmail($event);
    public function sendForgotPasswordEmail($userEmail, $temporaryPassword);
    public function sendPasswordChangedEmail($user);
}