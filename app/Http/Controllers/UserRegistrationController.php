<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppUser;

class UserRegistrationController extends Controller
{


    function register(Request $request)
    {
        $resp = array();

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $emailAddress = $request->input('email_address');
        $phoneNumber = $request->input('phone_number');
        $password = $request->input('password');


        //Checks if email address is already used
        $username_check = AppUser::where('email_address', $emailAddress)->take(1)->get();

        if ($username_check->count() > 0) {
            $resp['msg'] = 'Email address already taken';
            $resp['id'] = 0;
            $resp['error'] = 2;
            $resp['success'] = 0;
        } else {

            $user = new AppUser();
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email_address = $emailAddress;
            $user->phone_number = $phoneNumber;
            $user->password = md5($password);

            if ($user->save()) {
                $resp['msg'] = 'User registration successful';
                $resp['id'] = $user->customer_id;
                $resp['user'] = ['first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email_address' => $user->email_address,
                    'phone_number' => $user->phone_number];
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'User registration Process failed';
                $resp['id'] = 0;
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        }

        return json_encode($resp);
    }

    function unique_aar_id()
    {
        # prevent the first number from being 0
        $output = rand(1, 9);

        for ($i = 0; $i < 10; $i++) {
            $output .= rand(0, 9);
        }

        return $output;
    }

    function update(Request $request)
    {
        $resp = array();

        $userId = $request->input('user_id');
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $emailAddress = $request->input('email_address');
        $phoneNumber = $request->input('phone_number');

        $user = AppUser::find($userId);

        if ($firstName != null && $firstName != $user->first_name) {
            $user->first_name = $firstName;
        }

        if ($lastName != null && $lastName != $user->last_name) {
            $user->last_name = $lastName;
        }

        if ($emailAddress != null && $emailAddress != $user->email_address) {
            $user->email_address = $emailAddress;
        }

        if ($phoneNumber != null && $phoneNumber != $user->phone_number) {
            $user->phone_number = $phoneNumber;
        }

        if ($emailAddress != null && $emailAddress != $user->email_address) {
            $user->email_address = $emailAddress;
        }

        if ($user->save()) {
            $resp['msg'] = 'Changes saved successful';
            $resp['id'] = $user->customer_id;
            $resp['user'] = ['first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email_address' => $user->email_address,
                'phone_number' => $user->phone_number];
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Profile changes Process failed';
            $resp['id'] = 0;
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;

    }

    function updatePassword(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $password = $request->input('password');

        $user = AppUser::find($customer_id);

        $resp = array();
        if ($password != null) {
            $user->password = md5($password);
            if ($user->save()) {
                $resp['msg'] = 'Password updated';
                $resp['error'] = 0;
                $resp['success'] = 1;

            } else {
                $resp['msg'] = 'Password update failed';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Password field empty';
            $resp['error'] = 2;
            $resp['success'] = 0;
        }

        return $resp;

    }

    //Gets users for Admin client
    function getAllUsers()
    {
        $users = AppUser::all();
        $resp = array();
        if ($users->count() > 0) {
            $userArray = array();
            foreach ($users as $user) {
                $userObject = array();
                $userObject["id"] = $user->customer_id;
                $userObject["first_name"] = $user->first_name;
                $userObject["last_name"] = $user->last_name;
                $userObject["email_address"] = $user->email_address;
                $userObject["phone_number"] = $user->phone_number;
                $userObject["created_at"] = $user->created_at;

                array_push($userArray, $userObject);
            }
            $resp["users"] = $userArray;
            $resp['msg'] = 'Found users';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Found no users';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

}
