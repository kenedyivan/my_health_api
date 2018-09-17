<?php

namespace App\Http\Controllers;

use App\Mail\ServiceRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendServiceEmailController extends Controller
{
    function sendEmail(){
        Mail::to("kenedyakenaivan@gmail.com")->send(new ServiceRequestMail());
        return "Sending email";
    }
}
