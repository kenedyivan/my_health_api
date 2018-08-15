<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmEntry extends Model
{
    protected $table = 'aar_alarm_entries';
    protected $primaryKey = 'alarm_entry_id';

    function frequencies(){
        return $this->hasMany('App\AlarmFrequency',
            'alarm_entry_id','alarm_entry_id');
    }
    
}
