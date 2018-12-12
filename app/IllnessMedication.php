<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IllnessMedication extends Model
{
    protected $table = 'arr_customer_illness_medications';
    protected $primaryKey = 'illness_medication_id';

    public function getMedicationDetails()
    {
        $medication = $this;
        return ['medication_id' => $medication->illness_medication_id,
            'illness_id' => $medication->customer_illness_id,
            'drug_name' => $medication->drug_name,
            'frequency' => $medication->frequency,
            'notes' => $medication->notes,
            'set_time' => $medication->set_time,
            'days' => $medication->days_frequency,
            'alarm_entry' => $this->getAlarmEntry($medication->illness_medication_id)
        ];
    }

    public function getAlarmEntry($medicationId)
    {
        $alarmEntry = AlarmEntry::where('medication_id', $medicationId)->where('type', 'illness')
            ->get();

        if ($alarmEntry->count() > 0) {
            $alarm = $alarmEntry[0];
            return $alarm->getAlarmEntryDetails();
        } else {
            return "none";
        }


    }
}
