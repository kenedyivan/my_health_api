<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $table = 'aar_customer_allergy';
    protected $primaryKey = 'customer_allergy_id';

    public function allergy_type()
    {
        return $this->hasOne('App\AllergyType','allergy_id','allergy_type_id');
    }

    public function hospital(){
        return $this->hasOne('App\Hospital',
            'hospital_id','hospital_id');
    }

    public function medications(){
        return $this->hasMany('App\AllergyMedication',
            'customer_allergy_id','customer_allergy_id');
    }
}
