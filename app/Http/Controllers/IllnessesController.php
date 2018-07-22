<?php

namespace App\Http\Controllers;

use App\Allergy;
use App\Illness;
use Illuminate\Http\Request;

class IllnessesController extends Controller
{
    function getIllnesses(Request $request)
    {
        $resp = array();

        $customer_id = $request->input('customer_id');

        $illnessList = array();
        $allergiesList = array();

        if ($customer_id == 0) {
            $resp['msg'] = 'No Customer Id found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            $illnesses = Illness::where('customer_id', $customer_id)
                ->orderBy('created_at', 'desc')->get();
            if ($illnesses->count() < 1) {
                $resp['msg_illness'] = 'No illnesses found';
                $resp['error_illness'] = 2;
                $resp['success_illness'] = 0;
            } else {
                foreach ($illnesses as $illness) {
                    $ill = array();
                    $ill["id"] = $illness->customer_illness_id;
                    $ill["disease"] = $illness->disease_type_id;
                    $ill["diagnosis"] = $illness->diagnosis;
                    $ill["t_date"] = $illness->t_date;
                    $ill["medication"] = $illness->medication;
                    $ill["notes"] = $illness->notes;
                    $ill["hospital"] = $illness->hospital_id;
                    $ill["created_at"] = $illness->created_at;

                    array_push($illnessList, $ill);
                }

                $resp['msg_illness'] = 'Illness list';
                $resp['illnesses'] = $illnessList;
                $resp['error_illness'] = 0;
                $resp['success_illness'] = 1;
            }

            $allergies = Allergy::where('customer_id', $customer_id)
                ->orderBy('created_at', 'desc')->get();

            if ($allergies->count() < 1) {
                $resp['msg_allergy'] = 'No allergies found';
                $resp['error_allergy'] = 2;
                $resp['success_allergy'] = 0;
            } else {
                foreach ($allergies as $allergy) {
                    $al = array();
                    $al["id"] = $allergy->customer_allergy_id;
                    $al["disease"] = $allergy->allergy_type_id;
                    $al["diagnosis"] = $allergy->diagnosis;
                    $al["t_date"] = $allergy->t_date;
                    $al["medication"] = $allergy->medication;
                    $al["notes"] = $allergy->notes;
                    $al["hospital"] = $allergy->hospital_id;
                    $al["created_at"] = $allergy->created_at;

                    array_push($allergiesList, $al);
                }

                $resp['msg_allergy'] = 'Allergies list';
                $resp['allergies'] = $allergiesList;
                $resp['error_allergy'] = 0;
                $resp['success_allergy'] = 1;
            }

            $resp['msg'] = 'Customer Id found';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return $resp;
    }

    function createIllness(Request $request)
    {
        $resp = array();

        $disease = $request->input('disease');
        $diagnosis = $request->input('diagnosis');
        $customer_id = $request->input('customer_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $medication = $request->input('medication');
        $hospital = $request->input('hospital');
        $notes = $request->input('notes');

        $illness = new Illness();

        $illness->customer_id = $customer_id;
        $illness->disease_type_id = $disease;
        $illness->diagnosis = $diagnosis;
        $illness->t_date = $year . '-' . $month . '-' . $day;
        $illness->medication = $medication;
        $illness->notes = $notes;
        $illness->hospital_id = $hospital;

        if ($illness->save()) {
            $resp['msg'] = 'Illness created successful';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed creating illness';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }
}
