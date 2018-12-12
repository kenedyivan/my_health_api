<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmFrequency extends Model
{
    protected $table = 'aar_alarm_frequencies';
    protected $primaryKey = 'alarm_frequency_id';

    public function getFrequencyDetails()
    {
        $frequency = $this;

        return ['id' => $frequency->alarm_frequency_id,
            'unique_alarm_id' => $frequency->unique_alarm_id,
            'day' => $frequency->day
        ];
    }
}
