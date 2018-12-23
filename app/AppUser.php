<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $table = 'aar_customer_account';
    protected $primaryKey = 'customer_id';


    public function illnesses()
    {
        return $this->hasMany('App\Illness',
            'customer_id', 'customer_id');
    }

    public function allergies()
    {
        return $this->hasMany('App\Allergy',
            'customer_id', 'customer_id');
    }

    public function getUserDetails()
    {
        $user = $this;
        return ['first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email_address' => $user->email_address,
            'phone_number' => $user->phone_number,
            'login_state' => $user->login_state];
    }
}
