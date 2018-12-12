<?php

namespace App\Http\Controllers;

use App\Allergy;
use App\Event;
use App\Illness;
use Illuminate\Http\Request;

class CustomerDataController extends Controller
{
    function getCustomerData(Request $request)
    {
        //Get the customer id
        $customerId = $request->input('customer_id');

        $resp['msg'] = 'User data';
        $resp['events'] = $this->getEvents($customerId);
        $resp['illnesses'] = $this->getCustomerIllnesses($customerId);
        $resp['allergies'] = $this->getCustomerAllergies($customerId);
        $resp['error'] = 0;
        $resp['success'] = 1;

        return json_encode($resp);
    }

    private function getEvents($customerId)
    {

        //Create an array object to hold the events
        $eventList = array();

        //Query the database given the customer id
        $events = Event::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')->get();

        //check if the events are available
        if ($events->count() < 1) {
            $hasEvents = false;
        } else {
            $hasEvents = true;
            //Events are available
            foreach ($events as $event) {

                $eventObject = $event->getEventDetails();

                array_push($eventList, $eventObject);
            }

        }


        if (!$hasEvents) {
            return array();
        }

        return $eventList;
    }

    function getCustomerIllnesses($customerId)
    {

        $illnesses = Illness::where('customer_id', $customerId)
            ->get();

        $illnessList = array();

        if ($illnesses->count() > 0) {
            $hasIllness = true;
            foreach ($illnesses as $illness) {
                $illnessObject = $illness->getIllnessDetails();

                array_push($illnessList, $illnessObject);
            }
        } else {
            $hasIllness = false;
        }


        if (!$hasIllness) {
            return array();
        }

        return $illnessList;
    }

    function getCustomerAllergies($customerId)
    {

        $allergies = Allergy::where('customer_id', $customerId)
            ->get();

        $allergiesList = array();

        if ($allergies->count() > 0) {
            $hasAllergies = true;
            foreach ($allergies as $allergy) {
                $allergyObject = $allergy->getAllergyDetails();

                array_push($allergiesList, $allergyObject);
            }

        } else {
            $hasAllergies = false;
        }

        if (!$hasAllergies) {
            return array();
        }
        return $allergiesList;
    }


}
