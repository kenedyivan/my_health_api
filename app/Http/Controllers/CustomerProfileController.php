<?php

namespace App\Http\Controllers;

use App\AppUser;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    function getCustomerProfile(Request $request){
        $customer_id = $request->input('customer_id');

        $user = AppUser::find($customer_id);
        $resp = array();
        if($user){
            $resp['msg'] = 'Customer profile data';
            $resp['id'] = $user->customer_id;
            $resp['user'] = ['first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'aar_id' => $user->aar_id,
                'email_address' => $user->email_address,
                'phone_number' => $user->phone_number];

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

            $allergiesList = array();
            foreach ($user->allergies as $allergy) {
                $al = array();
                $al["id"] = $allergy->customer_allergy_id;
                $al["disease"] = $allergy->allergy_type->al_name;
                $al["diagnosis"] = $allergy->diagnosis;
                $al["t_date"] = $allergy->t_date;
                $al["notes"] = $allergy->notes;
                $al["created_at"] = $allergy->created_at;

                array_push($allergiesList, $al);
            }
            $resp['allergies'] = $allergiesList;

            $resp['illness'] =
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Invalid customer id';
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return $resp;
    }

}
