<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'aar_customer_event';
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->belongsTo('App\AppUser', 'customer_id');
    }

    public function event_type()
    {
        return $this->hasOne('App\EventType',
            'event_type_id', 'event_type_id');
    }

    public function hospital(){
        return $this->hasOne('App\Hospital',
            'hospital_id','hospital_id');
    }
}
