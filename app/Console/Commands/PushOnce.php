<?php

namespace App\Console\Commands;

use App\Event;
use DateTime;
use Illuminate\Console\Command;

class PushOnce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push notification once';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->eventsBroadcast();
        $this->info('Once sent');
    }

    function eventsBroadcast()
    {
        $eventList = array();
        $events = Event::where('set_date', '<>', null)->where('repeat_sequence', 1)
            ->where('is_notification_sent', 0)
            ->orderBy('created_at', 'desc')->get();

        foreach ($events as $event) {
            $ev = array();
            $ev['set_date_time'] = $event->set_date . ' ' . $event->set_time;

            if (new DateTime() > new DateTime($ev['set_date_time'])) {
                $state = 'your time is passed';
                $this->sendOnce($event);
            } else {
                $state = 'your time not passed';
            }
        }

        return $eventList;
    }

    private function sendOnce($event)
    {
        $repeat = $event->repeat_sequence;
        $is_sent_flag = $event->is_notification_sent;

        if ($repeat == 1) { //once
            if ($is_sent_flag == 0) {
                $this->pushOnce($event->id, $event->customer->fcm_device_token, $event->title);
                $event->is_notification_sent = 1;
                $event->save();
            }

        }
    }

    private function pushOnce($id, $token, $message)
    {
        $this->sender($id, $token, $message);
    }

    function sender($id, $fcm_token, $message)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        //$token = "c2YA86KqCpM:APA91bGPldCXppJP84hs8ZSUqyflUuVlHdO2aOz4ckDJSJ4Y1m8ssG5C7yDAXLqdciixwHn3gCVyz7uXssLV-GZSEPlxKL3OsuBiMQENoQIdu7EJtEc9ak119UByK_9i_ZVBHlf6z97Cu3gGP3OdHOIl-nM3EYzSrg";
        $token = $fcm_token;
        $serverKey = 'AAAAploB1QM:APA91bHuQoYT-Ct8J6IpzYKqWDVYdNd91vqtEKHrdQ7sqjMOoXA3P873gXe2hGzHNAWRi7vU92iYtGNYd03UCyMWBRirgKWD0OXtPmO4pD5-MXTEQXdNUCoMXqmrkr7LoN8NB3JOJfvg4R7QR0iO54Rj4VajVcGwXA';
        $title = "My Health";
        $body = $message;
        $notification = array('title' => $title, 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);

        return $response;

    }
}
