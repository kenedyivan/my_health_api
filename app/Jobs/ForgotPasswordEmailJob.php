<?php

namespace App\Jobs;

use App\EmailHandler\EmailHandlerFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ForgotPasswordEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailAddress;
    protected $temporaryPassword;

    /**
     * Create a new job instance.
     *
     * @param $emailAddress
     * @param $temporaryPassword
     */
    public function __construct($emailAddress, $temporaryPassword)
    {
        $this->emailAddress = $emailAddress;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailHandler = EmailHandlerFactory::createEmailHandler();
        $emailHandler->sendForgotPasswordEmail($this->emailAddress, $this->temporaryPassword);
    }
}
