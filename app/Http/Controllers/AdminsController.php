<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    function getAdminUsers()
    {

        $resp = array();

        $admins = Admin::all();

        if ($admins->count() > 0) {
            $adminsArray = array();

            $resp['msg'] = 'Allergy medications';

            foreach ($admins as $admin) {
                $adminObject['admin_id'] = $admin->admin_id;
                $adminObject['first_name'] = $admin->first_name;
                $adminObject['last_name'] = $admin->last_name;
                $adminObject['username'] = $admin->username;
                $adminObject['email'] = $admin->email;
                $adminObject['phone'] = $admin->phone;

                array_push($adminsArray, $adminObject);
            }

            $resp['error'] = 0;
            $resp['success'] = 1;
            $resp['admin_users'] = $adminsArray;

        } else {
            $resp['msg'] = 'No admin users found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function showAdminUser($adminId)
    {

        $resp = array();

        if ($adminId >= 1) {
            $admin = Admin::find($adminId);

            if ($admin) {
                $resp['msg'] = 'Admin user';
                $resp['admin_data'] = [
                    'admin_id' => $admin->admin_id,
                    'first_name' => $admin->first_name,
                    'last_name' => $admin->last_name,
                    'username' => $admin->username,
                    'email' => $admin->email,
                    'phone' => $admin->phone
                ];
                $resp['error'] = 0;
                $resp['success'] = 1;
            } else {
                $resp['msg'] = 'Admin user with Id ' . $adminId . ' not found';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid admin user Id';
            $resp['error'] = 2;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function saveAdminUser(Request $request)
    {
        $resp = array();

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $username = $request->input('username');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');

        if ($first_name == '') {
            $resp['msg'] = 'Admin first name cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        if ($last_name == '') {
            $resp['msg'] = 'Admin last name cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        if ($username == '') {
            $resp['msg'] = 'Admin username cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        if ($email == '') {
            $resp['msg'] = 'Admin email cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        if ($phone == '') {
            $resp['msg'] = 'Admin phone cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        if ($password == '') {
            $resp['msg'] = 'Admin password cannot be empty';
            $resp['error'] = 45;
            $resp['success'] = 0;
            return $resp;
        }

        $admin = new Admin();


        $admin->first_name = $first_name;
        $admin->last_name = $last_name;
        $admin->username = $username;
        $admin->email = $email;
        $admin->phone = $phone;
        $admin->password = md5($password);

        if ($admin->save()) {
            $resp['msg'] = 'Admin  added successfully';

            $resp['admin_data'] = [
                'admin_id' => $admin->admin_id,
                'first_name' => $admin->first_name,
                'last_name' => $admin->last_name,
                'username' => $admin->username,
                'email' => $admin->email,
                'phone' => $admin->phone
            ];

            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Adding admin user failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function updateAdminUser(Request $request, $adminId)
    {
        $resp = array();

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $username = $request->input('username');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');

        if ($adminId >= 1) {

            $admin = Admin::find($adminId);

            if ($admin) {
                if ($first_name != '')
                    $admin->first_name = $first_name;

                if ($last_name != '')
                    $admin->last_name = $last_name;

                if ($username != '')
                    $admin->username = $username;

                if ($email != '')
                    $admin->email = $email;

                if ($phone != '')
                    $admin->phone = $phone;

                if ($password != '')
                    $admin->password = md5($password);

                if ($admin->save()) {
                    $resp['msg'] = 'Changes saved successfully';

                    $resp['admin_data'] = [
                        'admin_id' => $admin->admin_id,
                        'first_name' => $admin->first_name,
                        'last_name' => $admin->last_name,
                        'username' => $admin->username,
                        'email' => $admin->email,
                        'phone' => $admin->phone
                    ];

                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Failed saving admin user data';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Admin user with Id ' . $adminId . ' not found';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }

        } else {
            $resp['msg'] = 'Invalid admin user Id';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function deleteAdminUser($adminId)
    {
        if ($adminId >= 1) {
            $admin = Admin::find($adminId);

            if ($admin) {
                if ($admin->delete()) {
                    $resp['msg'] = 'Deleted';
                    $resp['error'] = 0;
                    $resp['success'] = 1;
                } else {
                    $resp['msg'] = 'Delete failed';
                    $resp['error'] = 1;
                    $resp['success'] = 0;
                }
            } else {
                $resp['msg'] = 'Admin user with Id ' . $adminId . ' not found';
                $resp['error'] = 1;
                $resp['success'] = 0;
            }
        } else {
            $resp['msg'] = 'Invalid admin user Id';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }
}
