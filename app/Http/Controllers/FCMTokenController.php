<?php

namespace App\Http\Controllers;

use App\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FCMTokenController extends Controller
{
    function updateDeviceToken(Request $request)
    {
        $resp = array();
        $customer_id = $request->input('customer_id');
        $fcm_device_token = $request->input('fcm_token');

        $user = AppUser::find($customer_id);

        $user->fcm_device_token = $fcm_device_token;

        if ($user->save()) {
            $resp['msg'] = 'Device token updated successfully';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed updating device token';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function sendMessage($message)
    {

        $this->sender();
    }

    function sender()
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = "c2YA86KqCpM:APA91bGPldCXppJP84hs8ZSUqyflUuVlHdO2aOz4ckDJSJ4Y1m8ssG5C7yDAXLqdciixwHn3gCVyz7uXssLV-GZSEPlxKL3OsuBiMQENoQIdu7EJtEc9ak119UByK_9i_ZVBHlf6z97Cu3gGP3OdHOIl-nM3EYzSrg";
        $serverKey = 'AAAAploB1QM:APA91bHuQoYT-Ct8J6IpzYKqWDVYdNd91vqtEKHrdQ7sqjMOoXA3P873gXe2hGzHNAWRi7vU92iYtGNYd03UCyMWBRirgKWD0OXtPmO4pD5-MXTEQXdNUCoMXqmrkr7LoN8NB3JOJfvg4R7QR0iO54Rj4VajVcGwXA';
        $title = "SAMPLE";
        $body = "Hello I am from Your php server";
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
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
