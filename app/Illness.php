<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Illness extends Model
{
    protected $table = 'aar_customer_illness';
    protected $primaryKey = 'customer_illness_id';

    public function disease_type()
    {
        return $this->hasOne('App\DiseaseType',
        'disease_id','disease_type_id');
    }

    public function hospital(){
        return $this->hasOne('App\Hospital',
            'hospital_id','hospital_id');
    }

}
