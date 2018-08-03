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
}
