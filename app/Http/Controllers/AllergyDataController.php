<?php

namespace App\Http\Controllers;

use App\AllergyData;
use Illuminate\Http\Request;

class AllergyDataController extends Controller
{
    function getAllergiesData(){
        $allergies = AllergyData::all();

        if ($allergies->count() < 1) {
            $resp['msg'] = 'No allergies found';
            $resp['error'] = 2;
            $resp['success'] = 0;
        } else {
            $allergyArray = array();
            foreach ($allergies as $allergy) {
                $allergyObject = array();
                $allergyObject["id"] = $allergy->allergy_id;
                $allergyObject["name"] = $allergy->al_name;
                $allergyObject["description"] = $allergy->al_description;
                $allergyObject["created_at"] = $allergy->created_at;

                array_push($allergyArray, $allergyObject);
            }


            $resp['msg'] = 'Allergies list';
            $resp['allergies'] = $allergyArray;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return $resp;
    }
}
