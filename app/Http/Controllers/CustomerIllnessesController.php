<?php

namespace App\Http\Controllers;

use App\AppUser;
use App\Illness;
use Illuminate\Http\Request;

class CustomerIllnessesController extends Controller
{
    function getCustomerIllnesses($userId)
    {
        $resp = array();
        $customer_id = $userId;

        $user = AppUser::find($customer_id);


        if ($customer_id >= 1) {
            if ($user) {
                if ($user->illnesses->count() > 0) {

                    $resp['msg'] = 'Illness list';

                    $illnessList = array();

                    foreach ($user->illnesses as $illness) {
                        $ill = array();
                        $ill["id"] = $illness->customer_illness_id;
                        $ill["disease"] = $illness->disease_type->d_name;
                        $ill["diagnosis"] = $illness->diagnosis;
                        $ill["t_date"] = $illness->t_date;
                        $ill["notes"] = $illness->notes;
                        $ill["created_at"] = $illness->created_at;

                        array_push($illnessList, $ill);
                    }

                    $resp['illnesses'] = $illnessList;
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'No illnesses found';
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

    function showCustomerIllness($userId, $id)
    {
        $resp = array();

        $customer_illness_id = $id;

        $illness = Illness::find($customer_illness_id);

        if ($customer_illness_id >= 1) {
            if ($illness) {
                $item_array['id'] = $illness->customer_illness_id;
                $item_array['disease'] = $illness->disease_type->d_name;
                $item_array['diagnosis'] = $illness->diagnosis;
                $item_array['t_date'] = $illness->t_date;
                $item_array['notes'] = $illness->notes;
                $item_array['created_at'] = $illness->created_at;

                $resp['msg'] = 'Illness data';
                $resp['illness'] = [
                    'id' => $illness->customer_illness_id,
                    'disease' => $illness->disease_type->d_name,
                    'diagnosis' => $illness->diagnosis,
                    't_date' => $illness->t_date,
                    'notes' => $illness->notes,
                    'created_at' => $illness->created_at
                ];
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Illness with Id ' . $id . ' not found';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid illness Id';
            $resp['error'] = 2;
            $resp['success'] = 0;
        }

        return $resp;

    }

    function saveCustomerIllness(Request $request, $userId)
    {
        $resp = array();

        $disease_type_id = $request->input('disease_type_id');
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

        if ($disease_type_id == '') {
            $resp['msg'] = 'Disease type id cannot be empty';
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
                $illness = new Illness();
                $illness->customer_id = $customer_id;
                $illness->disease_type_id = $disease_type_id;
                $illness->diagnosis = $diagnosis;
                $illness->t_date = $treatment_date;
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

    function updateCustomerIllness(Request $request, $userId, $id)
    {
        $resp = array();

        $customer_illness_id = $id;

        if ($customer_illness_id >= 1) {
            $illness_id = $request->input('illness_id');
            $diagnosis = $request->input('diagnosis');
            $treatment_date = $request->input('treatment_date');
            $notes = $request->input('notes');

            $illness = Illness::find($customer_illness_id);

            if ($illness) {

                if ($illness_id != null || $illness_id = '')
                    $illness->disease_type_id = $illness_id;
                if ($diagnosis != null || $diagnosis = '')
                    $illness->diagnosis = $diagnosis;
                if ($treatment_date != null || $treatment_date != '')
                    $illness->t_date = $treatment_date;
                if ($notes != null || $notes != '')
                    $illness->notes = $notes;

                if ($illness->save()) {

                    $resp['msg'] = 'Customer illness changes saved successful';
                    $resp['data'] = [
                        'id' => $illness->customer_illness_id,
                        'illness_type_id' => $illness->disease_type_id,
                        'illness' => $illness->disease_type->d_name,
                        'diagnosis' => $illness->diagnosis,
                        't_date' => $illness->t_date,
                        'notes' => $illness->notes,
                    ];
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Failed saving customer illness changes';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Illness with Id ' . $customer_illness_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid illness Id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function deleteCustomerIllness($userId, $id)
    {
        $resp = array();

        $customer_illness_id = $id;

        if ($customer_illness_id >= 1) {
            $illness = Illness::find($customer_illness_id);
            if ($illness) {
                if ($illness->delete()) {
                    $resp['msg'] = 'Deleted';
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Deleted faild';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Illness with Id ' . $customer_illness_id . ' not found';
                $resp['error'] = 2;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid customer illness Id';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;
    }
}