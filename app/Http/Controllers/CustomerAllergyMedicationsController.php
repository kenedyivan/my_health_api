<?php

namespace App\Http\Controllers;

use App\Allergy;
use App\AllergyMedication;
use Illuminate\Http\Request;

class CustomerAllergyMedicationsController extends Controller
{
    function getAllergyMedications($userId, $allergyId)
    {
        $resp = array();

        $customer_allergy_id = $allergyId;

        if ($customer_allergy_id >= 1) {

            $allergy = Allergy::find($customer_allergy_id);

            if ($allergy) {

                if ($allergy->medications->count() > 0) {
                    $medications = array();
                    foreach ($allergy->medications as $medication) {
                        $medicationDataArray = $medication->getMedicationDetails();
                        array_push($medications, $medicationDataArray);
                    }

                    $resp['msg'] = 'Allergy medications';
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                    $resp['medications'] = $medications;
                } else {
                    $resp['msg'] = 'No medications found';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }

            } else {
                $resp['msg'] = 'Allergy with Id ' . $customer_allergy_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid allergy id';
            $resp['error'] = 2;
            $resp['success'] = 0;
        }

        return $resp;

    }

    function showAllergyMedication($userId, $allergyId, $medicationId){
        $resp = array();

        if($medicationId >= 1){
            $medications = AllergyMedication::where('customer_allergy_id', $allergyId)
                ->where('allergy_medication_id', $medicationId)->take(1)->get();

            if ($medications->count() > 0) {

                $medication = $medications[0];

                $resp['msg'] = 'Allergy medication data';

                $resp['data'] = $medication->getMedicationDetails();

                $resp['error'] = 0;
                $resp['success'] = 1;
            }else{
                $resp['msg'] = 'Allergy medication with id ' . $medicationId . ' for allergy with id '.$allergyId.' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }
        }else{
            $resp['msg'] = 'Invalid allergy medication id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }


    function saveAllergyMedication(Request $request, $userId, $allergyId)
    {
        $resp = array();

        $customer_allergy_id = $request->input('customer_allergy_id');
        $drug_name = $request->input('drug_name');
        $frequency = $request->input('frequency');
        $notes = $request->input('notes');
        $set_time = $request->input('set_time');
        $days_frequency = $request->input('days_frequency');

        if ($customer_allergy_id == '') {
            $resp['msg'] = 'Customer allergy id cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        if ($drug_name == '') {
            $resp['msg'] = 'Drug name cannot be empty';
            $resp['error'] = 1;
            $resp['success'] = 0;
            return $resp;
        }

        if ($frequency == '') {
            $resp['msg'] = 'Frequency cannot be empty';
            $resp['error'] = 1;
            $resp['success'] = 0;
            return $resp;
        }

        if ($notes == '') {
            $resp['msg'] = 'Notes cannot be empty';
            $resp['error'] = 1;
            $resp['success'] = 0;
            return $resp;
        }

        if ($set_time == '') {
            $resp['msg'] = 'Set time cannot be empty';
            $resp['error'] = 1;
            $resp['success'] = 0;
            return $resp;
        }

        if ($days_frequency == '') {
            $resp['msg'] = 'Days frequency cannot be empty';
            $resp['error'] = 1;
            $resp['success'] = 0;
            return $resp;
        }

        if ($allergyId >= 1) {

            $allergy = Allergy::find($allergyId);

            if ($allergy) {

                $medication = new AllergyMedication();
                $medication->customer_allergy_id = $customer_allergy_id;
                $medication->drug_name = $drug_name;
                $medication->frequency = $frequency;
                $medication->notes = $notes;
                $medication->set_time = $set_time;
                $medication->days_frequency = $days_frequency;

                if ($medication->save()) {

                    $resp['msg'] = 'Medication added successfully';

                    $resp['data'] = $medication->getMedicationDetails();

                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Adding medication failed';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Allergy with id ' . $allergyId . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid allergy id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }


        return $resp;
    }

    function updateAllergyMedication(Request $request, $userId, $allergyId, $medicationId)
    {
        $resp = array();

        $customer_allergy_id = $request->input('customer_allergy_id');
        $medication_id = $request->input('medication_id');
        $drug_name = $request->input('drug_name');
        $frequency = $request->input('frequency');
        $notes = $request->input('notes');
        $set_time = $request->input('set_time');
        $days_frequency = $request->input('days_frequency');

        if ($allergyId >= 1) {
            $allergy = Allergy::find($allergyId);
            if ($allergy) {

                if ($medicationId >= 1) {

                    $medications = AllergyMedication::where('customer_allergy_id', $allergyId)
                        ->where('allergy_medication_id', $medicationId)->take(1)->get();

                    if ($medications->count() > 0) {

                        $medication = $medications[0];

                        if ($drug_name != '' && $medication->drug_name != $drug_name)
                            $medication->drug_name = $drug_name;

                        if ($frequency != '' && $medication->frequency != $frequency)
                            $medication->frequency = $frequency;

                        if ($notes != '' && $medication->notes != $notes)
                            $medication->notes = $notes;

                        if ($set_time != '' && $medication->set_time != $set_time)
                            $medication->set_time = $set_time;

                        if ($days_frequency != '' && $medication->set_time != $days_frequency)
                            $medication->days_frequency;

                        if ($medication->save()) {
                            $resp['msg'] = 'Changes saved successfully';
                            $resp['error'] = 0;
                            $resp['success'] = 1;
                        } else {
                            $resp['msg'] = 'Saving failed';
                            $resp['error'] = 1;
                            $resp['success'] = 0;
                        }

                    } else {
                        $resp['msg'] = 'Allergy medication with id ' . $medicationId . ' for allergy with id '.$allergyId.' not found';
                        $resp['error'] = 2;
                        $resp['success'] = 0;
                    }
                } else {
                    $resp['msg'] = 'Invalid allergy medication id';
                    $resp['error'] = 3;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Allergy with id ' . $allergyId . ' not found';
                $resp['error'] = 4;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid allergy id';
            $resp['error'] = 5;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function deleteAllergyMedication($userId, $allergyId, $medicationId){

        $resp = array();

        if($medicationId >= 1){

            $medications = AllergyMedication::where('customer_allergy_id', $allergyId)
                ->where('allergy_medication_id', $medicationId)->take(1)->get();

            if ($medications->count() > 0) {

                $medication = $medications[0];

                if ($medication->delete()) {
                    $resp['msg'] = 'Deleted';
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Delete failed';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            }else{
                $resp['msg'] = 'Allergy medication with id ' . $medicationId . ' for allergy with id '.$allergyId.' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        }else{
            $resp['msg'] = 'Invalid allergy medication id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }
}
