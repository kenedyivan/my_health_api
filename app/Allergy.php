<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $table = 'aar_customer_allergy';
    protected $primaryKey = 'customer_allergy_id';

    public function allergy_type()
    {
        return $this->hasOne('App\AllergyType', 'allergy_id', 'allergy_type_id');
    }

    public function hospital()
    {
        return $this->hasOne('App\Hospital',
            'hospital_id', 'hospital_id');
    }

    public function medications()
    {
        return $this->hasMany('App\AllergyMedication',
            'customer_allergy_id', 'customer_allergy_id');
    }

    public function getAllergyDetails()
    {
        $allergy = $this;

        return ["id" => $allergy->customer_allergy_id,
            "disease_type" => $allergy->allergy_type->al_name,
            "diagnosis" => $allergy->diagnosis,
            "t_date" => $allergy->t_date,
            "notes" => $allergy->notes,
            "created_at" => $allergy->created_at,
            "medications" => $this->getAllergyMedications($allergy->customer_allergy_id)
        ];
    }

    function getAllergyMedications($allergyId)
    {
        $resp = array();

        $customerAllergyId = $allergyId;

        $allergy = Allergy::find($customerAllergyId);

        $medications = array();
        foreach ($allergy->medications as $medication) {
            $medicationDataArray = $medication->getMedicationDetails();
            array_push($medications, $medicationDataArray);
        }

        return $medications;

    }
}
