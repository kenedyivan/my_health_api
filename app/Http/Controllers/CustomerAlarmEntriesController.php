<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlarmEntry;
use App\IllnessMedication;
use App\AllergyMedication;
use App\AlarmFrequency;

class CustomerAlarmEntriesController extends Controller
{
    function createAlarmEntry(Request $request)
    {
        $resp = array();
        $type = $request->input('type');
        $medicationId = $request->input('medication_id');
        $myHealthId = $request->input('my_health_id');
        $customerId = $request->input('customer_id');
        $daysFrequency = $request->input('days_frequency');
        $uniqueAlarmId = $request->input('unique_alarm_id');
        $setTime = $request->input('set_time');

        $requestContent = $request->getContent();
        parent::logger("User medication alarm request, User id = $customerId, request content = $requestContent");

        $alarm = new AlarmEntry();
        if ($type == 0) {
            $alarm->type = "illness";
            $problem_type = "illness";
            $medication = IllnessMedication::find($medicationId);
        } else if ($type == 1) {
            $alarm->type = "allergy";
            $problem_type = "allergy";
            $medication = AllergyMedication::find($medicationId);
        }
        $alarm->medication_id = $medicationId;
        $alarm->my_health_id = $myHealthId;
        $alarm->customer_id = $customerId;
        $alarm->set_time = $setTime;
        $alarm->days_frequency = $daysFrequency;

        //Updates days frequency and set time in medications table
        $medication->days_frequency = $daysFrequency;
        $medication->set_time = $setTime;
        $medication->save();

        parent::logger("Updated user health problem medication time = $setTime, days = $daysFrequency");

        if ($alarm->save()) {
            $days_arr = array();
            $days_arr = explode(" ", $daysFrequency);
            $entry_id = $alarm->alarm_entry_id;

            foreach ($days_arr as $day) {
                if ($day == "Monday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 1;
                    $frequency->day = "monday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Tuesday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 2;
                    $frequency->day = "tuesday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Wednesday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 3;
                    $frequency->day = "wednesday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Thursday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 4;
                    $frequency->day = "thursday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Friday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 5;
                    $frequency->day = "friday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Saturday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 6;
                    $frequency->day = "saturday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Sunday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 7;
                    $frequency->day = "sunday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                } else if ($day == "Everyday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->unique_alarm_id = $uniqueAlarmId + 8;
                    $frequency->day = "everyday";
                    $frequency->save();
                    parent::logger("Saved user medication alarm day = $day");
                }
            }

            $frequency_array = array();
            $set_frequencies = AlarmFrequency::where('alarm_entry_id', $entry_id)->get();
            if ($set_frequencies->count() > 0) {
                foreach ($set_frequencies as $freqs) {
                    $freq_arr = array();
                    $freq_arr['id'] = $freqs->alarm_frequency_id;
                    $freq_arr['type'] = $problem_type;
                    $freq_arr['medication_id'] = $medicationId;
                    $freq_arr['unique_alarm_id'] = $freqs->unique_alarm_id;
                    $freq_arr['day'] = $freqs->day;
                    array_push($frequency_array, $freq_arr);
                }
                $resp['frequencies'] = $frequency_array;
            }

            $resp['msg'] = 'Reminder saved successfully';
            $resp['id'] = $entry_id;
            $resp['medication_id'] = $medicationId;
            $resp['type'] = $problem_type;
            $resp['set_time'] = $setTime;
            $resp['error'] = 0;
            $resp['success'] = 1;
            parent::logger("Saved user medication alarm, User id = $customerId");
        } else {
            $resp['msg'] = 'Saving failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
            parent::logger("Failed to save medication alarm, User id = $customerId");
        }

        $responseString = json_encode($resp);
        parent::logger("User medication alarm added response, User id = $customerId, response = $responseString");
        return $resp;

    }

    function updateAlarmEntry(Request $request)
    {
        $alarm_entry_id = $request->input('alarm_entry_id');
        $days_frequency = $request->input('days_frequency');
        $set_time = $request->input('set_time');

        $alarmEntry = AlarmEntry::find($alarm_entry_id);

        $alarmFrequencies = AlarmFrequency::where('alarm_entry_id', $alarm_entry_id);
        $alarmFrequencies->delete();

        $alarmEntry->set_time = $set_time;
        $alarmEntry->days_frequency = $days_frequency;

        $medication = '';
        if ($alarmEntry->type == "illness") {
            $medication = IllnessMedication::find($alarmEntry->medication_id);
        } else if ($alarmEntry->type == "allergy") {
            $medication = AllergyMedication::find($alarmEntry->medication_id);
        }

        $medication->days_frequency = $days_frequency;
        $medication->set_time = $set_time;

        $medication->save();


        if ($alarmEntry->save()) {
            $days_arr = explode(" ", $days_frequency);
            $entry_id = $alarmEntry->alarm_entry_id;

            foreach ($days_arr as $day) {
                if ($day == "Monday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "monday";
                    $frequency->save();
                } else if ($day == "Tuesday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "tuesday";
                    $frequency->save();
                } else if ($day == "Wednesday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "wednesday";
                    $frequency->save();
                } else if ($day == "Thursday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "thursday";
                    $frequency->save();
                } else if ($day == "Friday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "friday";
                    $frequency->save();
                } else if ($day == "Saturday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "saturday";
                    $frequency->save();
                } else if ($day == "Sunday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "sunday";
                    $frequency->save();
                } else if ($day == "Everyday") {
                    $frequency = new AlarmFrequency();
                    $frequency->alarm_entry_id = $entry_id;
                    $frequency->day = "everyday";
                    $frequency->save();
                }
            }
            $frequency_array = array();
            $set_frequencies = AlarmFrequency::where('alarm_entry_id', $entry_id)->get();
            if ($set_frequencies->count() > 0) {
                foreach ($set_frequencies as $freqs) {
                    $freq_arr = array();
                    $freq_arr['id'] = $freqs->alarm_frequency_id;
                    $freq_arr['day'] = $freqs->day;
                    array_push($frequency_array, $freq_arr);
                }
                $resp['frequencies'] = $frequency_array;
            }

            $resp['msg'] = 'Reminder saved successfully';
            $resp['id'] = $entry_id;
            $resp['set_time'] = $set_time;
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Saving failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;


    }


    function getCustomerAlarmEntries(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $entries = AlarmEntry::where('customer_id', $customer_id)
            ->get();

        $resp = array();
        $entries_arr = array();
        $entries_array = array();
        $frequency_array = array();

        if ($entries->count() > 0) {
            foreach ($entries as $entry) {
                $entries_arr['id'] = $entry->alarm_entry_id;
                $entries_arr['set_time'] = $entry->set_time;

                foreach ($entry->frequencies as $freqs) {
                    $freq_arr = array();
                    $freq_arr['id'] = $freqs->alarm_frequency_id;
                    $freq_arr['day'] = $freqs->day;
                    array_push($frequency_array, $freq_arr);
                }
                $entries_arr['frequencies'] = $frequency_array;
                array_push($entries_array, $entries_arr);
            }
            $resp['entries'] = $entries_array;
            $resp['msg'] = 'Alarm entries';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'No entries found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }


        return $resp;

    }

    function getCustomerMedicationAlarmEntry(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $medication_id = $request->input('medication_id');
        $entries = AlarmEntry::where('customer_id', $customer_id)
            ->where('medication_id', $medication_id)
            ->get();

        $resp = array();
        $entryData = array();
        $frequency_array = array();

        if ($entries->count() > 0) {
            $entry = $entries[0];
            $entryData['id'] = $entry->alarm_entry_id;
            $entryData['set_time'] = $entry->set_time;

            foreach ($entry->frequencies as $freqs) {
                $freq_arr = array();
                $freq_arr['id'] = $freqs->alarm_frequency_id;
                $freq_arr['day'] = $freqs->day;
                array_push($frequency_array, $freq_arr);
            }
            $entryData['frequencies'] = $frequency_array;

            $resp['entry'] = $entryData;
            $resp['msg'] = 'Alarm entries';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'No entries found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }


        return $resp;
    }
}
