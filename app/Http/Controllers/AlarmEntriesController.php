<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlarmEntry;
use App\IllnessMedication;
use App\AllergyMedication;
use App\AlarmFrequency;

class AlarmEntriesController extends Controller
{
    function createAlarmEntry(Request $request){
        $resp = array();
        $type = $request->input('type');
        $medication_id = $request->input('medication_id');
        $my_health_id = $request->input('my_health_id');
        $customer_id = $request->input('customer_id');
        $days_frequency = $request->input('days_frequency');
        $set_time = $request->input('set_time');

        $alarm = new AlarmEntry();
        if($type == 0){
            $alarm->type = "illness";
            $medication = IllnessMedication::find($medication_id);
        }else if($type == 1){
            $alarm->type = "allergy";
            $medication = AllergyMedication::find($medication_id);
        }
        $alarm->medication_id = $medication_id;
        $alarm->my_health_id = $my_health_id;
        $alarm->customer_id = $customer_id;
        $alarm->set_time = $set_time;
        $alarm->days_frequency = $days_frequency;

        $medication->days_frequency = $days_frequency;
        $medication->set_time = $set_time;

        $medication->save();

        if($alarm->save()){
            $days_arr = array();
            $days_arr = explode(" ",$days_frequency);
            $entry_id = $alarm->alarm_entry_id;
        
            foreach($days_arr as $day){
                if($day == "Monday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "monday";
                    $frequency->save();
                }else if($day == "Tuesday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "tuesday";
                    $frequency->save();
                }else if($day == "Wednesday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "wednesday";
                    $frequency->save();
                }else if($day == "Thursday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "thursday";
                    $frequency->save();
                }else if($day == "Friday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "friday";
                    $frequency->save();
                }else if($day == "Saturday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "saturday";
                    $frequency->save();
                }else if($day == "Sunday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "sunday";
                    $frequency->save();
                }else if($day == "Everyday"){
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "everyday";
                    $frequency->save();
                }
            }

            $resp['msg'] = 'Reminder saved successfully';
            $resp['id'] = $entry_id;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }else{
            $resp['msg'] = 'Saving failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;

    }


    function getCustomerAlarmEntries(Request $request){
        $customer_id = $request->input('customer_id');
        $entries = AlarmEntry::where('customer_id',$customer_id)
            ->get();

        $resp = array();
        $entries_arr = array();
        foreach($entries as $entry){
            $entries_arr['entry']= $entry->set_time;

            foreach($entry->frequencies as $freqs){
                $freq_arr = array();
                $freq_arr['day'] = $freqs->day;
                array_push($entries_arr,$freq_arr);
            }
            array_push($resp,$entries_arr);
        }

        return $resp;

    }
}
