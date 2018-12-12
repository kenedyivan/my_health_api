<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmEntry extends Model
{
    protected $table = 'aar_alarm_entries';
    protected $primaryKey = 'alarm_entry_id';

    function frequencies()
    {
        return $this->hasMany('App\AlarmFrequency',
            'alarm_entry_id', 'alarm_entry_id');
    }

    public function getAlarmEntryDetails()
    {
        $alarm = $this;
        return [
            'alarm_entry_id' => $alarm->alarm_entry_id,
            'medication_id' => $alarm->medication_id,
            'set_time' => $alarm->set_time,
            'days_frequency' => $alarm->days_frequency,
            'frequencies' => $this->getAlarmFrequencies($alarm->alarm_entry_id, $alarm->type, $alarm->medication_id)
        ];
    }

    private function getAlarmFrequencies($alarmEntryId, $type, $medicationId)
    {
        $frequencyArray = array();
        $frequencies = AlarmFrequency::where('alarm_entry_id', $alarmEntryId)->get();
        if ($frequencies->count() > 0) {
            foreach ($frequencies as $frequency) {

                $frequencyDataArray = $frequency->getFrequencyDetails();
                $frequencyDataArray['type'] = $type;
                $frequencyDataArray['medication_id'] = $medicationId;

                array_push($frequencyArray, $frequencyDataArray);
            }
            return $frequencyArray;
        }else{
            return "none";
        }
    }

}
