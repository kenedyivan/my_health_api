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
    public function sendAppointmentEmail($event);
}