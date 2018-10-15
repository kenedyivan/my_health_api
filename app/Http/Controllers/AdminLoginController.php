<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    function login(Request $request)
    {
        $resp = array();

        $username = $request->input('username');
        $password = $request->input('password');

        $admin = Admin::where('username', $username)->take(1)
            ->get();

        if ($admin->count() > 0) {

            if (md5($password) == $admin[0]->password) {
                $resp['msg'] = 'Login successful';
                $resp['admin'] = ['id' => $admin[0]->admin_id,
                    'first_name' => $admin[0]->first_name,
                    'last_name' => $admin[0]->last_name,
                    'username' => $admin[0]->username,
                    'email_address' => $admin[0]->email,
                    'phone_number' => $admin[0]->phone];
                $resp['error'] = 0;
                $resp['success'] = 1;

            } else {
                $resp['msg'] = 'Incorrect password';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }

        } else {

            $resp['msg'] = 'Incorrect username';
            $resp['id'] = 0;
            $resp['error'] = 2;
            $resp['success'] = 0;

        }

        return $resp;
    }
}
