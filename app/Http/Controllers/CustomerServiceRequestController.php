<?php

namespace App\Http\Controllers;

use App\EmailHandler\EmailHandlerFactory;
use App\Mail\ServiceRequestMail;
use App\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerServiceRequestController extends Controller
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
        $serviceRequest->is_cancelled = 0;

        if ($serviceRequest->save()) {
            $this->sendServiceRequestEmail($serviceRequest);
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

    //Gest service requests for customer android client
    function getServices(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $resp = array();

        $services = ServiceRequest::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($services->count() > 0) {
            $servicesArray = array();
            foreach ($services as $service) {
                $serviceObject = array();
                $serviceObject["id"] = $service->service_request_id;
                $serviceObject["service_type"] = $service->service_type;
                $serviceObject["set_date"] = $service->set_date;
                $serviceObject["set_time"] = $service->set_time;
                $serviceObject["location"] = $service->location;

                array_push($servicesArray, $serviceObject);
            }

            $resp["services"] = $servicesArray;
            $resp['msg'] = 'Found services';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed retrieving services';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    //Gets service requests for admin client
    function getServicesAdmin(Request $request)
    {
        $resp = array();

        $services = ServiceRequest::where('customer_id','<>',0)->get();

        if ($services->count() > 0) {
            $servicesArray = array();
            foreach ($services as $service) {
                $serviceObject = array();
                $serviceObject["id"] = $service->service_request_id;
                $serviceObject["service_type"] = $service->service_type;
                $serviceObject["set_date"] = $service->set_date;
                $serviceObject["set_time"] = $service->set_time;
                $serviceObject["location"] = $service->location;
                $serviceObject["customer"] = $service->customer->username;
                $serviceObject["phone_number"] = $service->customer->phone_number;

                array_push($servicesArray, $serviceObject);
            }

            $resp["services"] = $servicesArray;
            $resp['msg'] = 'Found services';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'No services found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function cancelServiceRequest(Request $request)
    {
        $resp = array();
        $service_request_id = $request->input('service_request_id');

        $service = ServiceRequest::find($service_request_id);

        if ($service) {
            if ($service->is_cancelled == 1) {
                Log::info('Service request ' . $service->customer . ': '
                    . $service->service_type . ' already cancelled');
                $resp['msg'] = 'Service already cancelled';
                $resp['error'] = 1;
                $resp['success'] = 0;
            } else {
                $service->is_cancelled = 1;

                if ($service->save()) {
                    Log::info('Cancelled service request ' . $service->customer
                        . ': ' . $service->service_type);
                    $resp['msg'] = 'Service cancelled';
                    $resp['error'] = 0;
                    $resp['success'] = 1;

                    $this->sendServiceRequestCancelEmail($service);

                } else {
                    Log::debug('Failed cancelling service request ' . $service->customer . ': '
                        . $service->service_type);
                    $resp['msg'] = 'Failed cancelling service';
                    $resp['error'] = 2;
                    $resp['success'] = 0;
                }
            }
        } else {
            Log::debug('Service request with id ' . $service_request_id . ' not found');
            $resp['msg'] = 'Service not found';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function getServiceCustomer()
    {
        $service = ServiceRequest::find(13);
        return $service->customer;
    }

    private function sendServiceRequestEmail($service)
    {
        $emailHandler = EmailHandlerFactory::createEmailHandler();
        $emailHandler->sendServiceRequestEmail($service);
    }


    private function sendServiceRequestCancelEmail($service)
    {
        $emailHandler = EmailHandlerFactory::createEmailHandler();
        $emailHandler->sendServiceRequestCancelEmail($service);
    }
}
