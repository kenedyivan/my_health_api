<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IllnessMedication extends Model
{
    protected $table = 'arr_customer_illness_medications';
    protected $primaryKey = 'illness_medication_id';
}
