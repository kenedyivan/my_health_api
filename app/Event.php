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

    public function hospital()
    {
        return $this->hasOne('App\Hospital',
            'hospital_id', 'hospital_id');
    }

    public function getEventDetails()
    {
        $event = $this;
        return [
            'id' => $event->id,
            'title' => $event->title,
            'note' => $event->note,
            'unique_actual_alarm_id' => $event->unique_actual_alarm_id,
            'actual_date_time' => $event->actual_date_time,
            'before_ten_mins_id' => $event->before_ten_mins_id == null ? 0 : $event->before_ten_mins_id,
            'before_ten_mins' => $event->before_ten_mins,
            'before_thirty_mins_id' => $event->before_thirty_mins_id == null ? 0 : $event->before_thirty_mins_id,
            'before_thirty_mins' => $event->before_thirty_mins,
            'before_one_hour_id' => $event->before_one_hour_id == null ? 0 : $event->before_one_hour_id,
            'before_one_hour' => $event->before_one_hour,
            'before_one_day_id' => $event->before_one_day_id == null ? 0 : $event->before_one_day_id,
            'before_one_day' => $event->before_one_day,
            'repeat' => $event->repeat_sequence,
            'location' => $event->location,
            'event_type' => $event->event_type->event_type,
            'is_cancelled' => $event->is_cancelled
        ];
    }
}
