<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Illness extends Model
{
    protected $table = 'aar_customer_illness';
    protected $primaryKey = 'customer_illness_id';

    public function disease_type()
    {
        return $this->hasOne('App\DiseaseType',
            'disease_id', 'disease_type_id');
    }

    public function hospital()
    {
        return $this->hasOne('App\Hospital',
            'hospital_id', 'hospital_id');
    }

    public function medications()
    {
        return $this->hasMany('App\IllnessMedication',
            'customer_illness_id', 'customer_illness_id');
    }

    public function getIllnessDetails()
    {
        $illness = $this;
        return ["id" => $illness->customer_illness_id,
            "disease_type" => $illness->disease_type->d_name,
            "diagnosis" => $illness->diagnosis,
            "t_date" => $illness->t_date,
            "notes" => $illness->notes,
            "created_at" => $illness->created_at,
            "medications" => $this->getIllnessMedications($illness->customer_illness_id)
        ];
    }

    public function getIllnessMedications($illnessId)
    {
        $customerIllnessId = $illnessId;

        $illness = Illness::find($customerIllnessId);

        $medications = array();

        foreach ($illness->medications as $medication) {
            $medicationObject = $medication->getMedicationDetails();
            array_push($medications, $medicationObject);
        }


        return $medications;

    }
}
