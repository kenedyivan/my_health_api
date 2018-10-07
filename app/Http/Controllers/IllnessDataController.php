<?php

namespace App\Http\Controllers;

use App\Disease;
use Illuminate\Http\Request;

class IllnessDataController extends Controller
{
    function getIllnessData(){
        $diseases = Disease::all();
        $resp = array();
        if ($diseases->count() < 1) {
            $resp['msg'] = 'No illnesses found';
            $resp['error'] = 2;
            $resp['success'] = 0;
        } else {
            $diseaseArray = array();
            foreach ($diseases as $disease) {
                $diseaseObject = array();
                $diseaseObject["id"] = $disease->disease_id;
                $diseaseObject["name"] = $disease->d_name;
                $diseaseObject["description"] = $disease->d_description;
                $diseaseObject["created_at"] = $disease->created_at;

                array_push($diseaseArray, $diseaseObject);
            }

            $resp['msg'] = 'Illness list';
            $resp['illnesses'] = $diseaseArray;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }
        
        return $resp;
    }
}
