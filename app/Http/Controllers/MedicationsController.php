<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IllnessMedication;
use App\AllergyMedication;

class MedicationsController extends Controller
{
    function save(Request $request){
        $resp = array();

        $my_health_id = $request->input('my_health_id');
        $type = $request->input('type');
        $drug_name = $request->input('drug_name');
        $frequency = $request->input('frequency');
        $notes = $request->input('notes');

        $medication = null;
        if($type == 0){
            $medication = new IllnessMedication();
            $medication->customer_illness_id = $my_health_id;
        }else if($type == 1){
            $medication = new AllergyMedication();
            $medication->customer_allergy_id = $my_health_id;
        }

        $medication->drug_name = $drug_name;
        $medication->frequency = $frequency;
        $medication->notes = $notes;

        if($medication->save()){
            $resp['msg'] = 'Medication added successfully';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Adding failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }
    }

    function update(Request $request){
        $resp = array();
        $my_health_id = $request->input('my_health_id');
        $type = $request->input('type');
        $medication_id = $request->input('medication_id');
        $drug_name = $request->input('drug_name');
        $frequency = $request->input('frequency');
        $notes = $request->input('notes');

        if($type == 0){
            $medication = IllnessMedication::find($medication_id);
        }else if($type == 1){
            $medication = AllergyMedication::find($medication_id);
        }

        if($drug_name != '' && $medication->drug_name != $drug_name)
            $medication->drug_name = $drug_name;
        
        if($frequency != '' && $medication->frequency != $frequency)
            $medication->frequency = $frequency;

        if($notes != '' && $medication->notes != $notes)
            $medication->notes = $notes;

        if($medication->save()){
            $resp['msg'] = 'Changes saved successfully';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Saving failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;

    }

    function delete(Request $request){
        $resp = array();

        $medication_id = $request->input('medication_id');
        $type = $request->input('type');
        $medication = null;
        if($type == 0){
            $medication = IllnessMedication::find($medication_id);
        }else if($type == 1){
            $medication = AllergyMedication::find($medication_id);
        }
            
        if($medication->delete()){
            $resp['msg'] = 'Deleted';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Delete failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }
        

        return $resp;
    }

    function reminder(Request $request){
        $resp = array();
        $type = $request->input('type');
        $medication_id = $request->input('medication_id');
        $days_frequency = $request->input('days_frequency');
        $set_time = $request->input('set_time');

        if($type == 0){
            $medication = IllnessMedication::find($medication_id);
        }else if($type == 1){
            $medication = AllergyMedication::find($medication_id);
        }

        $medication->days_frequency = $days_frequency;
        $medication->set_time = $set_time;

        if($medication->save()){
            $resp['msg'] = 'Reminder saved successfully';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Saving failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;

    }
}
