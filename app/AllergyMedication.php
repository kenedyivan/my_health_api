<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllergyMedication extends Model
{
    protected $table = 'aar_customer_allergy_medications';
    protected $primaryKey = 'allergy_medication_id';

    public function getMedicationDetails()
    {
        $medication = $this;

        return ['medication_id' => $medication->allergy_medication_id,
            'allergy_id' => $medication->customer_allergy_id,
            'drug_name' => $medication->drug_name,
            'frequency' => $medication->frequency,
            'notes' => $medication->notes,
            'set_time' => $medication->set_time,
            'days' => $medication->days_frequency,
            'alarm_entry' => $this->getAlarmEntry($medication->allergy_medication_id)
        ];
    }

    public function getAlarmEntry($medicationId)
    {
        $alarmEntry = AlarmEntry::where('medication_id', $medicationId)->where('type', 'allergy')
            ->get();

        if($alarmEntry->count() > 0){
            $alarm = $alarmEntry[0];
            return $alarm->getAlarmEntryDetails();
        }else{
            return "none";
        }


    }

}
