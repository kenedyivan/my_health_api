<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppUser;

class UserRegistrationController extends Controller
{


    function register(Request $request){
        $resp = array();

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $username = $request->input('username');
        $date_of_birth = $request->input('date_of_birth');
        $email_address = $request->input('email_address');
        $phone_number = $request->input('phone_number');
        $password = $request->input('password');


        //Checks if username is already used
        $username_check = AppUser::where('username',$username)->take(1)->get();

        if($username_check->count() > 0){
            $resp['msg'] = 'Username already taken';
            $resp['id'] = 0;
            $resp['error'] = 2;
            $resp['success'] = 0;
        }else{
            $aar_id = '';
            while(true){
                //Generates new aar_id
                $aar_id = $this->unique_aar_id();

                //Checks if aar_id already used
                $aar_id_check = AppUser::where('aar_id',$aar_id)->take(1)->get();
                if($aar_id_check->count() != 1){
                    break;
                }
            }

            $user = new AppUser();
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->username = $username;
            $user->date_of_birth = $date_of_birth;
            $user->aar_id = $aar_id;
            $user->email_address = $email_address;
            $user->phone_number = $phone_number;
            $user->password = md5($password);

            if($user->save()){
                $resp['msg'] = 'User registration successful';
                $resp['id'] = $user->customer_id;
                $resp['user'] = ['first_name'=>$user->first_name,
                    'last_name'=>$user->last_name,
                    'username'=>$user->username,
                    'date_of_birth'=>$user->date_of_birth,
                    'aar_id'=>$user->aar_id,
                    'email_address'=>$user->email_address,
                    'phone_number'=>$user->phone_number];
                $resp['error'] = 0;
                $resp['success'] = 1;
            }else{
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

}
