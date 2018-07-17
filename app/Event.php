<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'aar_customer_event';
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->belongsTo('App\AppUser','customer_id');
    }
}
