<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $table = 'aar_medications';
    protected $primaryKey = 'medication_id';
}
