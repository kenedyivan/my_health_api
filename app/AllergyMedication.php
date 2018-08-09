<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllergyMedication extends Model
{
    protected $table = 'aar_customer_allergy_medications';
    protected $primaryKey = 'allergy_medication_id';
}
