<?php

namespace App\Http\Controllers;

use App\Traits\LoginMode;
use Illuminate\Http\Request;
use App\AppUser;

class UserLoginController extends Controller
{
    use LoginMode;

    function login(Request $request)
    {
        $resp = array();
        $number = 1;

        $emailAddress = $request->input('email_address');
        $password = $request->input('password');

        $user = AppUser::where('email_address', $emailAddress)->take($number)
            ->get();

        if ($user->count() > 0) {

            $user = $user[0];

            if ($user->login_state == $this->NORMAL_MODE) { //Normal Mode
                parent::logger("Email identified, Email id = { $emailAddress }, login mode = { Normal mode }");
                if (md5($password) == $user->password) {
                    parent::logger("Login successful, Email id = { $emailAddress }");
                    $resp['msg'] = 'Login successful';
                    $resp['id'] = $user->customer_id;
                    $resp['user'] = $resp['user'] = $user->getUserDetails();
                    $resp['mode'] = 'normal';
                    $resp['error'] = 0;
                    $resp['success'] = 1;

                } else {
                    parent::logger("Incorrect password, Email id = { $emailAddress }");
                    $resp['msg'] = 'Incorrect password';
                    $resp['id'] = 0;
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else if ($user->login_state == $this->RECOVERY_MODE) { //Recovery Mode
                parent::logger("Email identified, Email id = { $emailAddress }, login mode = { Recovery mode }");
                if (md5($password) == $user->temporary_password) {

                    $user->temporary_password = null;
                    $user->login_state = 0;
                    $user->save();
                    parent::logger("Reset login mode from Recovery mode to Normal mode, Email id = { $emailAddress }");

                    parent::logger("Login successful, Email id = { $emailAddress }");
                    $resp['msg'] = 'Login successful';
                    $resp['id'] = $user->customer_id;
                    $resp['user'] = $user->getUserDetails();
                    $resp['mode'] = 'recovery';
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
