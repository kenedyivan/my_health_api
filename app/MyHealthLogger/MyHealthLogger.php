<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 12/13/18
 * Time: 12:38 PM
 */

namespace App\MyHealthLogger;


use Katzgrau\KLogger\Logger;

class MyHealthLogger
{
    private $logger;
    private $tag;

    /**
     * PaymentsController constructor.
     */
    public function __construct()
    {
        $path = storage_path('logs/transaction_logs.log');

        try {
            $this->tag = (new \ReflectionClass($this))->getShortName();
        } catch (\ReflectionException $e) {
            $this->logger->error($this->tag . ' - ' . $e->getMessage());
        }
        $this->logger = new Logger($path);

    }


    public function log($tag, $message)
    {
        $this->logger->info("$tag - $message");
    }
}