<?php

namespace App\Http\Controllers;

use App\Disease;
use App\AllergyType;
use App\Hospital;
use Illuminate\Http\Request;

class PageDataController extends Controller
{
    function getHospitals()
    {
        $hospitals = Hospital::all();
        $diseases = Disease::all();
        $allergies = AllergyType::all();

        $resp = array();
        $hospitalList = array();
        $diseaseList = array();
        $allergyList = array();

        if ($hospitals->count() < 1) {
            $resp['msg'] = 'No hospitals found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            foreach ($hospitals as $hospital) {
                $hos = array();
                $hos["id"] = $hospital->hospital_id;
                $hos["name"] = $hospital->name;
                $hos["address"] = $hospital->address;

                array_push($hospitalList, $hos);
            }

            $resp['msg'] = 'Illness list';
            $resp['hospitals'] = $hospitalList;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        if ($diseases->count() < 1) {
            $resp['msg'] = 'No diseases found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            foreach ($diseases as $disease) {
                $di = array();
                $di["id"] = $disease->disease_id;
                $di["name"] = $disease->d_name;
                $di["description"] = $disease->d_description;

                array_push($diseaseList, $di);
            }

            $resp['msg'] = 'Disease list';
            $resp['diseases'] = $diseaseList;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        if ($allergies->count() < 1) {
            $resp['msg'] = 'No allergies found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            foreach ($allergies as $allergy) {
                $al = array();
                $al["id"] = $allergy->allergy_id;
                $al["name"] = $allergy->al_name;
                $al["description"] = $allergy->al_description;

                array_push($allergyList, $al);
            }

            $resp['msg'] = 'Allergy list';
            $resp['allergies'] = $allergyList;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }


        return $resp;

    }

}
