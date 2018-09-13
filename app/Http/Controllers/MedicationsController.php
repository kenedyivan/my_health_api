<?php

namespace App\Http\Controllers;

use App\Medication;
use Illuminate\Http\Request;

class MedicationsController extends Controller
{
    function getMedicationList(){
        $resp = array();

        $medications = Medication::where('generic_names','<>','null')
            ->get();
        if($medications->count() > 0){
            $resp['medicines'] = $medications;
            $resp['msg'] = 'Data found';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'No data found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;

    }
}
