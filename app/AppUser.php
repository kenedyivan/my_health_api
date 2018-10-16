<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $table = 'aar_customer_account';
    protected $primaryKey = 'customer_id';


    public function illnesses(){
        return $this->hasMany('App\Illness',
            'customer_id', 'customer_id');
    }

    public function allergies(){
        return $this->hasMany('App\Allergy',
            'customer_id', 'customer_id');
    }
}
