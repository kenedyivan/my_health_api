<?php

namespace App\Http\Controllers;

use App\Allergy;
use App\Illness;
use Illuminate\Http\Request;

class CustomerIllnessesControllerOrig extends Controller
{
    function show(Request $request)
    {
        $resp = array();
        $item_array = array();
        $my_health_id = $request->input('my_health_id');
        $type = $request->input('type');

        if ($type == 0) {
            $illness = Illness::find($my_health_id);
            $item_array['id'] = $illness->customer_illness_id;
            $item_array['disease'] = $illness->disease_type->d_name;
            $item_array['diagnosis'] = $illness->diagnosis;
            $item_array['date'] = $illness->t_date;
            $item_array['notes'] = $illness->notes;
            $medications = array();
            foreach ($illness->medications as $medication) {
                $med_array = array();
                $med_array['medication_id'] = $medication->illness_medication_id;
                $med_array['drug_name'] = $medication->drug_name;
                $med_array['frequency'] = $medication->frequency;
                $med_array['notes'] = $medication->notes;
                $med_array['set_time'] = $medication->set_time;
                $med_array['days'] = $medication->days_frequency;
                array_push($medications, $med_array);
            }

            $item_array['medications'] = $medications;
            $item_array['created_at'] = $illness->created_at;

        } else {
            $allergy = Allergy::find($my_health_id);
            $item_array['id'] = $allergy->customer_allergy_id;
            $item_array['disease'] = $allergy->allergy_type->al_name;
            $item_array['diagnosis'] = $allergy->diagnosis;
            $item_array['date'] = $allergy->t_date;
            $item_array['notes'] = $allergy->notes;
            $medications = array();
            foreach ($allergy->medications as $medication) {
                $med_array = array();
                $med_array['medication_id'] = $medication->allergy_medication_id;
                $med_array['drug_name'] = $medication->drug_name;
                $med_array['frequency'] = $medication->frequency;
                $med_array['notes'] = $medication->notes;
                $med_array['set_time'] = $medication->set_time;
                $med_array['days'] = $medication->days_frequency;
                array_push($medications, $med_array);
            }

            $item_array['medications'] = $medications;
            $item_array['created_at'] = $allergy->created_at;
        }

        $resp['item'] = $item_array;

        return $resp;

    }

    function delete(Request $request)
    {

        $resp = array();

        $my_heal_id = $request->input('my_health_id');
        $type = $request->input('type');

        if ($type == "ILLNESS") {
            $illness = Illness::find($my_heal_id);
            if ($illness->delete()) {
                $resp['msg'] = 'Deleted';
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Deleted faild';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }

        } else if($type == "ALLERGY"){
            $allergy = Allergy::find($my_heal_id);
            if ($allergy->delete()) {
                $resp['msg'] = 'Deleted';
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Delete failed';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        }

        return $resp;
    }

    function getIllnesses(Request $request)
    {
        $resp = array();
        $mergedArray = array();

        $customer_id = $request->input('customer_id');

        if ($customer_id == 0) {
            $resp['msg'] = 'No Customer Id found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {

            $resp['msg'] = 'Customer Id found';
            $resp['error'] = 0;
            $resp['success'] = 1;
            $mergedArray = array_merge($resp, $this->getIllnessAndAllergies($customer_id));
        }

        return $mergedArray;
    }

    function createIllness(Request $request)
    {
        $resp = array();
        $page = $request->input('page');
        $disease = $request->input('disease');
        $diagnosis = $request->input('diagnosis');
        $customer_id = $request->input('customer_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $notes = $request->input('notes');

        if ($page == 0) {
            $illness = new Illness();
            $illness->customer_id = $customer_id;
            $illness->disease_type_id = $disease;
            $illness->diagnosis = $diagnosis;
            $illness->t_date = $year . '-' . $month . '-' . $day;
            $illness->notes = $notes;

            if ($illness->save()) {
                $resp['data'] = [
                    'id' => $illness->customer_illness_id,
                    'disease_type' => $illness->disease_type->d_name,
                    'diagnosis' => $illness->diagnosis,
                    't_date' => $illness->t_date,
                    'notes' => $illness->notes,
                ];
                $resp['type'] = 'illness';
                $resp['msg'] = 'Customer illness created successful';
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Failed creating customer illness';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        } else if ($page == 1) {
            $allergy = new Allergy();
            $allergy->customer_id = $customer_id;
            $allergy->allergy_type_id = $disease;
            $allergy->diagnosis = $diagnosis;
            $allergy->t_date = $year . '-' . $month . '-' . $day;
            $allergy->notes = $notes;

            if ($allergy->save()) {
                $resp['data'] = [
                    'id' => $allergy->customer_allergy_id,
                    'disease_type' => $allergy->allergy_type->al_name,
                    'diagnosis' => $allergy->diagnosis,
                    't_date' => $allergy->t_date,
                    'notes' => $allergy->notes,
                ];
                $resp['type'] = 'allergy';
                $resp['msg'] = 'Customer allergy created successful';
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Failed creating customer allergy';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        }

        return $resp;
    }

    function update(Request $request)
    {
        $resp = array();
        $mergedArray = array();
        $customer_id = $request->input('customer_id');
        $page = $request->input('page');
        $disease = $request->input('disease');
        $diagnosis = $request->input('diagnosis');
        $my_health_id = $request->input('my_health_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $notes = $request->input('notes');

        if ($page == 0) {
            $illness = Illness::find($my_health_id);
            $illness->disease_type_id = $disease;
            $illness->diagnosis = $diagnosis;
            $illness->t_date = $year . '-' . $month . '-' . $day;
            $illness->notes = $notes;

            if ($illness->save()) {
                $resp['msg'] = 'Customer illness changes saved successful';
                $resp['data'] = [
                    'id' => $illness->customer_illness_id,
                    'disease' => $illness->disease_type->d_name,
                    'diagnosis' => $illness->diagnosis,
                    't_date' => $illness->t_date,
                    'notes' => $illness->notes,
                ];
                $resp['error'] = 0;
                $resp['type'] = "ILLNESS";
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Failed saving customer illness changes';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        } else if ($page == 1) {
            $allergy = Allergy::find($my_health_id);
            $allergy->allergy_type_id = $disease;
            $allergy->diagnosis = $diagnosis;
            $allergy->t_date = $year . '-' . $month . '-' . $day;
            $allergy->notes = $notes;

            if ($allergy->save()) {
                $resp['msg'] = 'Customer allergy changes saved successful';
                $resp['data'] = [
                    'id' => $allergy->customer_allergy_id,
                    'disease' => $allergy->allergy_type->al_name,
                    'diagnosis' => $allergy->diagnosis,
                    't_date' => $allergy->t_date,
                    'notes' => $allergy->notes,
                ];
                $resp['error'] = 0;
                $resp['type'] = "ALLERGY";
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Failed saving customer allergy changes';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        }


        $mergedArray = array_merge($resp, $this->getIllnessAndAllergies($customer_id));
        return $mergedArray;


    }

    function getIllnessAndAllergies($customer_id)
    {
        $resp = array();
        $illnessList = array();
        $allergiesList = array();

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
                $ill["disease"] = $illness->disease_type->d_name;
                $ill["diagnosis"] = $illness->diagnosis;
                $ill["t_date"] = $illness->t_date;
                $ill["notes"] = $illness->notes;
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
                $al["disease"] = $allergy->allergy_type->al_name;
                $al["diagnosis"] = $allergy->diagnosis;
                $al["t_date"] = $allergy->t_date;
                $al["notes"] = $allergy->notes;
                $al["created_at"] = $allergy->created_at;

                array_push($allergiesList, $al);
            }


            $resp['msg_allergy'] = 'Allergies list';
            $resp['allergies'] = $allergiesList;
            $resp['error_allergy'] = 0;
            $resp['success_allergy'] = 1;
        }

        return $resp;
    }
}
