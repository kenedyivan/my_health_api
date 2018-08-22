<?php

namespace App\Http\Controllers;

use App\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    function create(Request $request)
    {
        $resp = array();

        $customer_id = $request->input('customer_id');
        $service_type = $request->input('service_type');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $hour = $request->input('hour');
        $minute = $request->input('minute');
        $location = $request->input('location');

        $serviceRequest = new ServiceRequest();

        $serviceRequest->customer_id = $customer_id;
        $serviceRequest->service_type = $service_type;
        $serviceRequest->set_date = $year . '-' . $month . '-' . $day;;
        $serviceRequest->set_time = $hour . ':' . $minute;
        $serviceRequest->location = $location;

        if ($serviceRequest->save()) {
            $resp['msg'] = 'Service request created successfully';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed creating service request';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }
}
