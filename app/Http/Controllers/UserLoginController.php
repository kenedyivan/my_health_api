<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppUser;

class UserLoginController extends Controller
{
    function login(Request $request)
    {
        $resp = array();

        $emailAddress = $request->input('email_address');
        $password = $request->input('password');

        $user = AppUser::where('email_address', $emailAddress)->take(1)
            ->get();

        if ($user->count() > 0) {

            $user = $user[0];

            if ($user->login_state == 0) { //Recovery mode
                parent::logger("Email identified, Email id = { $emailAddress }, login mode = { Recovery mode }");
                if (md5($password) == $user->password) {
                    parent::logger("Login successful, Email id = { $emailAddress }");
                    $resp['msg'] = 'Login successful';
                    $resp['id'] = $user->customer_id;
                    $resp['user'] = ['first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email_address' => $user->email_address,
                        'phone_number' => $user->phone_number];
                    $resp['error'] = 0;
                    $resp['success'] = 1;

                } else {
                    parent::logger("Incorrect password, Email id = { $emailAddress }");
                    $resp['msg'] = 'Incorrect password';
                    $resp['id'] = 0;
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else if ($user->login_state == 1) { //Normal mode
                parent::logger("Email identified, Email id = { $emailAddress }, login mode = { Normal mode }");
                if (md5($password) == $user->temporary_password) {

                    $user->temporary_password = null;
                    $user->login_state = 0;
                    $user->save();
                    parent::logger("Reset login mode from Recovery mode to Normal mode, Email id = { $emailAddress }");

                    parent::logger("Login successful, Email id = { $emailAddress }");
                    $resp['msg'] = 'Login successful';
                    $resp['id'] = $user->customer_id;
                    $resp['user'] = ['first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email_address' => $user->email_address,
                        'phone_number' => $user->phone_number];
                    $resp['error'] = 0;
                    $resp['success'] = 1;

                } else {
                    parent::logger("Incorrect password, Email id = { $emailAddress }");
                    $resp['msg'] = 'Incorrect password';
                    $resp['id'] = 0;
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                //No mode defined
                parent::logger("No login mode defined, Email id = { $emailAddress }");
                $resp['msg'] = 'No login mode defined';
                $resp['id'] = 0;
                $resp['error'] = 1;
                $resp['success'] = 0;
            }


        } else {
            parent::logger("Login failed, Email id = { $emailAddress }");
            $resp['msg'] = 'Incorrect username';
            $resp['id'] = 0;
            $resp['error'] = 2;
            $resp['success'] = 0;

        }

        return $resp;
    }
}
