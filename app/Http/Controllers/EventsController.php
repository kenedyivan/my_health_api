<?php

namespace App\Http\Controllers;

use App\Event;
use DateTime;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    function createEvent(Request $request)
    {
        $resp = array();

        $title = $request->input('title');
        $customer_id = $request->input('customer_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $hour = $request->input('hour');
        $minute = $request->input('minute');
        $repeat = $request->input('repeat');
        $location = $request->input('location');

        $event = new Event();

        $event->customer_id = $customer_id;
        $event->title = $title;
        $event->set_date = $year . '-' . $month . '-' . $day;
        $event->set_time = $hour . ':' . $minute;
        $event->repeat_sequence = $repeat;
        $event->location = $location;

        if ($event->save()) {
            $resp['msg'] = 'Event created successful';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed creating even';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }


    function getEventsList(Request $request)
    {
        $eventList = array();
        $events = Event::orderBy('created_at', 'desc')->get();

        if ($events->count() < 1) {
            $resp['msg'] = 'No events found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            foreach ($events as $event) {
                $ev = array();
                $ev['id'] = $event->id;
                $ev['title'] = $event->title;
                $ev['date'] = $event->set_date;
                $ev['time'] = $event->set_time;
                $ev['repeat'] = $event->repeat_sequence;
                $ev['location'] = $event->location;

                array_push($eventList, $ev);
            }

            $resp['msg'] = 'Events list';
            $resp['events'] = $eventList;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return $resp;
    }

    function eventsBroadcast()
    {
        $eventList = array();
        $events = Event::where('repeat_sequence', 1)
            ->where('is_notification_sent', 0)
            ->orderBy('created_at', 'desc')->get();

        foreach ($events as $event) {
            $ev = array();
            $ev['set_date_time'] = $event->set_date . ' ' . $event->set_time;

            if (new DateTime() > new DateTime($ev['set_date_time'])) {
                $state = 'your time is passed';
            } else {
                $state = 'your time not passed';
            }

            array_push($eventList,$ev);
        }

        return $eventList;
    }

    private function sendOnce($event)
    {
        $repeat = $event->repeat_sequence;
        $is_sent_flag = $event->is_notification_sent;

        if ($repeat == 1) { //once
            if ($is_sent_flag == 0) {
                $this->pushOnce();
                $event->is_notification_sent = 1;
                $event->save();
            }

        }
    }

    private function sendDaily($event)
    {
        $repeat = $event->repeat_sequence;
        $is_sent_flag = $event->is_notification_sent;
        if ($repeat == 2) { //daily
            if ($is_sent_flag == 0) {
                $this->pushDaily();
                $startDate = strtotime($event->next_hit_date_time);
                $next_date_time = date('Y-m-d H:i:s', strtotime('+1 day', $startDate));
                $event->next_hit_date_time = $next_date_time;
                $event->save();
            }
        }
    }

    private function sendWeekly($event)
    {
        $repeat = $event->repeat_sequence;
        $is_sent_flag = $event->is_notification_sent;
        if ($repeat == 3) { //daily
            if ($is_sent_flag == 0) {
                $this->pushWeekly();
                $startDate = strtotime($event->next_hit_date_time);
                $next_date_time = date('Y-m-d H:i:s', strtotime('+7 day', $startDate));
                $event->next_hit_date_time = $next_date_time;
                $event->save();
            }
        }
    }

    private function sendMonthly($event)
    {
        $repeat = $event->repeat_sequence;
        $is_sent_flag = $event->is_notification_sent;
        if ($repeat == 4) { //daily
            if ($is_sent_flag == 0) {
                $this->pushMonthly();
                $startDate = strtotime($event->next_hit_date_time);
                $next_date_time = date('Y-m-d H:i:s', strtotime('+30 day', $startDate));
                $event->next_hit_date_time = $next_date_time;
                $event->save();
            }
        }
    }

    private function pushOnce()
    {

    }

    private function pushDaily()
    {

    }

    private function pushWeekly()
    {

    }

    private function pushMonthly()
    {

    }
}
