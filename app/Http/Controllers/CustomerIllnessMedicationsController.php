<?php

namespace App\Http\Controllers;

use App\Illness;
use App\IllnessMedication;
use Illuminate\Http\Request;

class CustomerIllnessMedicationsController extends Controller
{
    function getIllnessMedications($userId, $illnessId)
    {
        $resp = array();

        $customer_illness_id = $illnessId;

        if ($customer_illness_id >= 1) {

            $illness = Illness::find($customer_illness_id);

            if ($illness) {

                if ($illness->medications->count() > 0) {
                    $medications = array();
                    foreach ($illness->medications as $medication) {
                        $medicationObject = $medication->getMedicationDetails();
                        array_push($medications, $medicationObject);
                    }

                    $resp['msg'] = 'Illness medications';
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                    $resp['medications'] = $medications;
                } else {
                    $resp['msg'] = 'No medications found';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }

            } else {
                $resp['msg'] = 'Illness with Id ' . $customer_illness_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid illness id';
            $resp['error'] = 2;
            $resp['success'] = 0;
        }

        return $resp;

    }

    function showIllnessMedication($userId, $illnessId, $medicationId)
    {
        $resp = array();

        if ($medicationId >= 1) {

            $medications = IllnessMedication::where('customer_illness_id', $illnessId)
                ->where('illness_medication_id', $medicationId)->take(1)->get();
            if ($medications->count() > 0) {

                $medication = $medications[0];

                $resp['msg'] = 'Illness medication data';

                $resp['data'] = $medication->getMedicationDetails();

                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Illness medication with id ' . $medicationId . ' for illness with id ' . $illnessId . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid illness medication id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function saveIllnessMedication(Request $request, $userId, $illnessId)
    {
        $resp = array();

        $customer_illness_id = $request->input('customer_illness_id');
        $drug_name = $request->input('drug_name');
        $frequency = $request->input('frequency');
        $notes = $request->input('notes');
        $set_time = $request->input('set_time');
        $days_frequency = $request->input('days_frequency');

        if ($customer_illness_id == '') {
            $resp['msg'] = 'Customer illness id cannot be empty';
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

        if ($illnessId >= 1) {

            $illness = Illness::find($illnessId);

            if ($illness) {

                $medication = new IllnessMedication();
                $medication->customer_illness_id = $customer_illness_id;
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
                    $resp['msg'] = 'Adding failed';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Illness with id ' . $illnessId . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid illness id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }


        return $resp;
    }

    function updateIllnessMedication(Request $request, $userId, $illnessId, $medicationId)
    {
        $resp = array();

        $customer_illness_id = $request->input('customer_illness_id');
        $medication_id = $request->input('medication_id');
        $drug_name = $request->input('drug_name');
        $frequency = $request->input('frequency');
        $notes = $request->input('notes');
        $set_time = $request->input('set_time');
        $days_frequency = $request->input('days_frequency');

        if ($illnessId >= 1) {
            $illness = Illness::find($illnessId);
            if ($illness) {

                if ($medicationId >= 1) {

                    $medications = IllnessMedication::where('customer_illness_id', $illnessId)
                        ->where('illness_medication_id', $medicationId)->take(1)->get();

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
                        $resp['msg'] = 'Illness medication with id ' . $medicationId . ' for illness with id ' . $illnessId . ' not found';
                        $resp['error'] = 2;
                        $resp['success'] = 0;
                    }
                } else {
                    $resp['msg'] = 'Invalid illness medication id';
                    $resp['error'] = 3;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Illness with id ' . $illnessId . ' not found';
                $resp['error'] = 4;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid illness id';
            $resp['error'] = 5;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function deleteIllnessMedication($userId, $illnessId, $medicationId)
    {

        $resp = array();

        if ($medicationId >= 1) {

            $medications = IllnessMedication::where('customer_illness_id', $illnessId)
                ->where('illness_medication_id', $medicationId)->take(1)->get();

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
            } else {
                $resp['msg'] = 'Illness medication with id ' . $medicationId . ' for illness with id ' . $illnessId . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid illness medication id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }


}
