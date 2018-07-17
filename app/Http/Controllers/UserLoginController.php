<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppUser;

class UserLoginController extends Controller
{
    function login(Request $request){
        $resp = array();

        $username = $request->input('username');
        $password = $request->input('password');

        $user = AppUser::where('username',$username)->take(1)
            ->get();

        if($user->count() > 0){

            if (md5($password) == $user[0]->password){
                $resp['msg'] = 'Login successful';
                $resp['id'] = $user[0]->customer_id;
                $resp['user'] = ['first_name'=>$user[0]->first_name,
                    'last_name'=>$user[0]->last_name,
                    'username'=>$user[0]->username,
                    'aar_id'=>$user[0]->aar_id,
                    'staff_id'=>$user[0]->staff_id,
                    'email_address'=>$user[0]->email_address,
                    'phone_number'=>$user[0]->phone_number];
                $resp['error'] = 0;
                $resp['success'] = 1;

            }else{
                $resp['msg'] = 'Incorrect password';
                $resp['id'] = 0;
                $resp['error'] = 1;
                $resp['success'] = 0;
            }

        }else{

            $resp['msg'] = 'Incorrect username';
            $resp['id'] = 0;
            $resp['error'] = 2;
            $resp['success'] = 0;

        }

        return $resp;
    }
}
