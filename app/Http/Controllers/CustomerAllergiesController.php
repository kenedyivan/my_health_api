<?php

namespace App\Http\Controllers;

use App\Allergy;
use App\AppUser;
use Illuminate\Http\Request;

class CustomerAllergiesController extends Controller
{
    function getCustomerAllergies($userId)
    {
        $resp = array();
        $customer_id = $userId;
        $user = AppUser::find($customer_id);

        if ($customer_id >= 1) {
            if ($user) {

                if ($user->allergies->count() > 0) {
                    $resp['msg'] = 'Customer allergies';

                    $allergiesList = array();

                    foreach ($user->allergies as $allergy) {
                        $allergyObject = $allergy->getAllergyDetails();

                        array_push($allergiesList, $allergyObject);
                    }
                    $resp['allergies'] = $allergiesList;

                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'No allergies found';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }

            } else {
                $resp['msg'] = 'Customer with Id ' . $customer_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid customer id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function showCustomerAllergy($userId, $id)
    {
        $customer_allergy_id = $id;

        $allergy = Allergy::find($customer_allergy_id);

        if ($customer_allergy_id >= 1) {
            if ($allergy) {

                $resp['msg'] = 'Allergy data';
                $resp['allergy'] = [
                    "id" => $allergy->customer_allergy_id,
                    "disease" => $allergy->allergy_type->al_name,
                    "diagnosis" => $allergy->diagnosis,
                    "t_date" => $allergy->t_date,
                    "notes" => $allergy->notes,
                    "created_at" => $allergy->created_at,
                ];
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Allergy with Id ' . $id . ' not found';
                $resp['error'] = 1;
                $resp['success'] = 0;

            }
        } else {
            $resp['msg'] = 'Invalid allergy Id';
            $resp['error'] = 2;
            $resp['success'] = 0;
        }


        return $resp;
    }

    function saveCustomerAllergy(Request $request, $userId)
    {
        $resp = array();
        $allergy_type_id = $request->input('allergy_type_id');
        $diagnosis = $request->input('diagnosis');
        $customer_id = $request->input('customer_id');
        $treatment_date = $request->input('treatment_date');
        $notes = $request->input('notes');

        if ($customer_id == '') {
            $resp['msg'] = 'Customer id cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 1;
            return $resp;
        }

        if ($allergy_type_id == '') {
            $resp['msg'] = 'Allergy type id cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 1;
            return $resp;
        }

        if ($diagnosis == '') {
            $resp['msg'] = 'diagnosis cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 1;
            return $resp;
        }

        if ($treatment_date == '') {
            $resp['msg'] = 'Treatment date cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 1;
            return $resp;
        }

        if ($notes == '') {
            $resp['msg'] = 'Notes cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 1;
            return $resp;
        }

        $customer = AppUser::find($customer_id);

        if ($customer_id >= 1) {
            if ($customer) {
                $allergy = new Allergy();
                $allergy->customer_id = $customer_id;
                $allergy->allergy_type_id = $allergy_type_id;
                $allergy->diagnosis = $diagnosis;
                $allergy->t_date = $treatment_date;
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
            } else {
                $resp['msg'] = 'Customer with Id ' . $customer_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid customer id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }


        return $resp;
    }

    function updateCustomerAllergy(Request $request, $userId, $id)
    {
        $resp = array();

        $customer_allergy_id = $id;

        if ($customer_allergy_id >= 1) {

            $allergy_id = $request->input('allergy_id');
            $diagnosis = $request->input('diagnosis');
            $treatment_date = $request->input('treatment_date');
            $notes = $request->input('notes');


            $allergy = Allergy::find($customer_allergy_id);
            if ($allergy) {

                //Checks for empty fields before setting object properties
                if ($allergy_id != null || $allergy_id != '')
                    $allergy->allergy_type_id = $allergy_id;
                if ($diagnosis != null || $diagnosis != '')
                    $allergy->diagnosis = $diagnosis;
                if ($treatment_date != null || $treatment_date != '')
                    $allergy->t_date = $treatment_date;
                if ($notes != null || $notes != '')
                    $allergy->notes = $notes;

                //Saves object data
                if ($allergy->save()) {
                    $resp['msg'] = 'Customer allergy changes saved successful';
                    $resp['data'] = [
                        'id' => $allergy->customer_allergy_id,
                        'allergy_type_id' => $allergy->allergy_type_id,
                        'allergy' => $allergy->allergy_type->al_name,
                        'diagnosis' => $allergy->diagnosis,
                        't_date' => $allergy->t_date,
                        'notes' => $allergy->notes,
                    ];
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Failed saving customer allergy changes';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Allergy with Id ' . $customer_allergy_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid allergy Id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }


        return $resp;


    }

    function deleteCustomerAllergy($userId, $id)
    {
        $resp = array();

        $customer_allergy_id = $id;

        if ($customer_allergy_id >= 1) {
            $allergy = Allergy::find($customer_allergy_id);
            if ($allergy) {
                if ($allergy->delete()) {
                    $resp['msg'] = 'Deleted';
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Delete failed';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Allergy with Id ' . $customer_allergy_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid customer allergy Id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }


        return $resp;
    }

}
