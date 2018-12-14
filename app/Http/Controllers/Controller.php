<?php

namespace App\Http\Controllers;

use App\MyHealthLogger\MyHealthLogger;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $tag;

    public function logger($message)
    {
        try {
            $this->tag = (new \ReflectionClass($this))->getShortName();
        } catch (\ReflectionException $e) {
           //Reflection error
        }

        $logger = new MyHealthLogger();
        $logger->log($this->tag, $message);
    }
}
