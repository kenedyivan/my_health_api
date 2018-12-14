<?php

namespace App\Http\Controllers;

use App\EmailHandler\EmailHandlerFactory;
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
            $user->login_state = 0;
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

    private function unique_aar_id()
    {
        # prevent the first number from being 0
        $output = rand(1, 9);

        for ($i = 0; $i < 10; $i++) {
            $output .= rand(0, 9);
        }

        return $output;
    }


    private function generateTemporaryPassword($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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

    function forgotPassword(Request $request)
    {
        $emailAddress = $request->input('email_address');

        $user = AppUser::where('email_address', $emailAddress)->get();

        if ($user->count() > 0) {
            $user = $user[0];
            //Generate temporary password
            $temporaryPassword = $this->generateTemporaryPassword();
            parent::logger("Generated temporary password, Email id = $emailAddress");
            $temporaryPasswordHash = md5($temporaryPassword);

            $user->temporary_password = $temporaryPasswordHash;
            $user->login_state = 1; //Recovery mode

            if ($user->save()) {
                parent::logger("Saved temporary password to database, Email id = $emailAddress");
                $emailHandler = EmailHandlerFactory::createEmailHandler();
                $emailHandler->sendForgotPasswordEmail($emailAddress, $temporaryPassword);

                $resp['msg'] = 'Temporary password generated';
                $resp['id'] = 0;
                $resp['error'] = 0;
                $resp['success'] = 1;

            } else {
                parent::logger("Failed to save temporary password to database, Email id = $emailAddress");
                $resp['msg'] = 'Failed to save temporary password';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }

        } else {
            parent::logger("Could not find email address in the database, Email id = $emailAddress");
            $resp['msg'] = 'Email not found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        $responseString = json_encode($resp);
        parent::logger("Forgot password process response, response = $responseString");
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
