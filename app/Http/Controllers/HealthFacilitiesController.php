<?php

namespace App\Http\Controllers;

use App\HealthFacility;
use Illuminate\Http\Request;

class HealthFacilitiesController extends Controller
{
    function getHealthFacilities(){
        $healthFacilities = HealthFacility::all();

        $resp = array();

        if($healthFacilities->count() > 0){
            $healthFacilitiesArray = array();
            foreach ($healthFacilities as $healthFacility) {
                $healthFacilityObject = array();
                $healthFacilityObject["id"] = $healthFacility->hospital_id;
                $healthFacilityObject["name"] = $healthFacility->name;
                $healthFacilityObject["address"] = $healthFacility->address;
                $healthFacilityObject["created_at"] = $healthFacility->created_at;

                array_push($healthFacilitiesArray, $healthFacilityObject);
            }
            $resp["health_facilities"] = $healthFacilitiesArray;
            $resp['msg'] = 'Found health facilities';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Found no health facilities';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return $resp;
    }
}
