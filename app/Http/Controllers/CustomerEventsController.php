<?php

namespace App\Http\Controllers;

use App\EmailHandler\EmailHandlerFactory;
use App\Event;
use App\Jobs\ReminderCreatedEmailJob;
use App\Jobs\ReminderReceivedConfirmationJob;
use App\Mail\ReminderMail;
use App\Mail\ReminderConfirmationMail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerEventsController extends Controller
{

    function createEvent(Request $request)
    {
        $resp = array();

        $eventType = $request->input('event_type');
        $title = $request->input('title');
        $customer_id = $request->input('customer_id');
        $actualDateTime = $request->input('actual_date_time');
        $before_ten_mins = $request->input('before_ten_mins');
        $before_thirty_mins = $request->input('before_thirty_mins');
        $before_one_hour = $request->input('before_one_hour');
        $before_one_day = $request->input('before_one_day');
        $repeat = $request->input('repeat');
        $location = $request->input('location');
        $unique_alarm_id = $request->input('unique_alarm_id');

        $requestBody = $request->getContent();

        parent::logger("User reminder data received, User ID = { $customer_id }, Request body = { $requestBody } ");
        parent::logger("User reminder actual date, User ID = { $customer_id }, Actual date = { $actualDateTime } ");

        $event = new Event();

        $ev = '';
        if ($eventType == "appointment") {
            $ev = 1;
        } else if ($eventType == "clinic_visit") {
            $ev = 2;
        } else if ($eventType == "reminder") {
            $ev = 3;
        }

        $event->event_type_id = $ev;
        $event->customer_id = $customer_id;
        $event->title = $title;

        $formattedActualDate = $this->getProperDateFromRange($actualDateTime);

        parent::logger("Formatted date = { $customer_id }, Actual date time = { $formattedActualDate } ");

        $event->actual_date_time = $formattedActualDate;

        $event->unique_actual_alarm_id = $unique_alarm_id;
        $event->before_ten_mins_id = $unique_alarm_id + 1;
        $event->before_thirty_mins_id = $unique_alarm_id + 2;
        $event->before_one_hour_id = $unique_alarm_id + 3;
        $event->before_one_day_id = $unique_alarm_id + 4;

        if ($before_ten_mins != "") {
            $formattedBeforeTenMins = $this->getProperDateFromRange($before_ten_mins);
            $event->before_ten_mins = $formattedBeforeTenMins;
            parent::logger("Formatted date = { $customer_id }, Before ten mins date time = { $formattedBeforeTenMins } ");
        }

        if ($before_thirty_mins != "") {
            $formattedBeforeThirtyMins = $this->getProperDateFromRange($before_thirty_mins);
            $event->before_thirty_mins = $formattedBeforeThirtyMins;
            parent::logger("Formatted date = { $customer_id }, Before thirty mins date time = { $formattedBeforeThirtyMins } ");
        }

        if ($before_one_hour != "") {
            $formattedBeforeOneHour = $this->getProperDateFromRange($before_one_hour);
            $event->before_one_hour = $formattedBeforeOneHour;
            parent::logger("Formatted date = { $customer_id }, Before one hour date time = { $formattedBeforeOneHour } ");
        }

        if ($before_one_day != "") {
            $formattedBeforeOneDay = $this->getProperDateFromRange($before_one_day);
            $event->before_one_day = $formattedBeforeOneDay;
            parent::logger("Formatted date = { $customer_id }, Before one day date time = { $formattedBeforeOneDay } ");
        }


        $event->repeat_sequence = $repeat;
        $event->location = $location;
        $event->is_cancelled = 0;
        $event->status = 'Pending';


        if ($event->save()) {
            parent::logger("User Reminder saved to database, User ID = { $customer_id }");
            $resp['msg'] = 'Event created successful';
            $resp['error'] = 0;
            $resp['success'] = 1;
            $resp['event'] = $this->parseEventDetails($event);

            if ($ev == 1 || $ev == 2) {
                $this->sendEventEmail($event);
                parent::logger("Administrator email for reminder created sent, Reminder ID = { $event->id }");
            }

        } else {
            parent::logger("Failed sending Administrator reminder created email, Reminder ID = { $event->id }");
            $resp['msg'] = 'Failed creating event';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        $responseString = json_encode($resp);
        parent::logger("Reminder created response, User ID = { $customer_id }, Response = { $responseString }");

        return $resp;
    }

    private function parseEventDetails($event)
    {
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

    function createEventWithHospitalId(Request $request)
    {
        $resp = array();

        $eventType = $request->input('event_type');
        $title = $request->input('title');
        $customer_id = $request->input('customer_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $hour = $request->input('hour');
        $minute = $request->input('minute');
        $notify_year = $request->input('notify_year');
        $notify_month = $request->input('notify_month');
        $notify_day = $request->input('notify_day');
        $notify_hour = $request->input('notify_hour');
        $notify_minute = $request->input('notify_minute');
        $repeat = $request->input('repeat');
        $location = $request->input('location');

        $event = new Event();

        $ev = '';
        if ($eventType == "appointment") {
            $ev = 1;
        } else if ($eventType == "clinic_visit") {
            $ev = 2;
        } else if ($eventType == "reminder") {
            $ev = 3;
        }

        $event->event_type_id = $ev;
        $event->customer_id = $customer_id;
        $event->title = $title;
        $event->set_date = $year . '-' . $month . '-' . $day;
        $event->set_time = $hour . ':' . $minute;
        $event->notify_date = $notify_year . '-' . $notify_month . '-'
            . $notify_day . ' ' . $notify_hour . ':' . $notify_minute;
        $event->repeat_sequence = $repeat;
        $event->hospital_id = $location;

        if ($event->save()) {
            $resp['msg'] = 'Event created successful';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed creating event';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function updateEvent(Request $request)
    {
        $resp = array();

        $event_id = $request->input('event_id');
        $eventType = $request->input('event_type');
        $title = $request->input('title');
        $customer_id = $request->input('customer_id');
        $actual_date_time = $request->input('actual_date_time');
        $before_ten_mins = $request->input('before_ten_mins');
        $before_thirty_mins = $request->input('before_thirty_mins');
        $before_one_hour = $request->input('before_one_hour');
        $before_one_day = $request->input('before_one_day');
        $repeat = $request->input('repeat');
        $location = $request->input('location');

        $event = Event::find($event_id);

        //$event->event_type_id = $ev;
        $event->customer_id = $customer_id;
        $event->title = $title;
        if ($event->actual_date_time != $actual_date_time) {
            $event->actual_date_time = $actual_date_time;

            if ($before_ten_mins != "") {
                $event->before_ten_mins = $before_ten_mins;
            }

            if ($before_thirty_mins != "") {
                $event->before_thirty_mins = $before_thirty_mins;
            }

            if ($before_one_hour != "") {
                $event->before_one_hour = $before_one_hour;
            }

            if ($before_one_day != "") {
                $event->before_one_day = $before_one_day;
            }


        } else {
            if ($before_ten_mins != "") {
                $event->before_ten_mins = $before_ten_mins;
            }

            if ($before_thirty_mins != "") {
                $event->before_thirty_mins = $before_thirty_mins;
            }

            if ($before_one_hour != "") {
                $event->before_one_hour = $before_one_hour;
            }

            if ($before_one_day != "") {
                $event->before_one_day = $before_one_day;
            }
        }

        //$event->unique_actual_alarm_id = $unique_alarm_id;


        $event->repeat_sequence = $repeat;
        $event->location = $location;

        if ($event->save()) {
            $resp['msg'] = 'Event udpated successful';
            $resp['event'] = $this->parseEventDetails($event);
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed updating event';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function saveComment(Request $request)
    {
        $resp = array();
        $event_id = $request->input('event_id');
        $comment = $request->input('comment');

        $event = Event::find($event_id);
        $event->note = $comment;

        if ($event->save()) {
            $resp['msg'] = 'Comment saved';
            $resp['event'] = $this->parseEventDetails($event);
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed saving comment';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    //Gets events for customer with
    function getEventsList(Request $request)
    {
        //Get the customer id
        $customer_id = $request->input('customer_id');

        //Create an array object to hold the events
        $eventList = array();

        //Query the database given the customer id
        $events = Event::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')->get();

        //check if the events are available
        if ($events->count() < 1) {
            $resp['msg'] = 'No events found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            //Events are available
            foreach ($events as $event) {

                $eventObject = $event->getEventDetails();

                array_push($eventList, $eventObject);
            }

            $resp['msg'] = 'Events list';
            $resp['events'] = $eventList;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return json_encode($resp);
    }

    // Gets events for admin client
    function getAllEventsList(Request $request)
    {
        //Create an array object to hold the events
        $eventList = array();

        //Query the database given the customer id
        $events = Event::where('customer_id', '<>', 0)->get();

        //check if the events are available
        if ($events->count() < 1) {
            $resp['msg'] = 'No events found';
            $resp['error'] = 1;
            $resp['success'] = 0;
        } else {
            //Events are available
            foreach ($events as $event) {
                $eventObject = $event->getEventDetails();

                array_push($eventList, $eventObject);
            }

            $resp['msg'] = 'Events list';
            $resp['events'] = $eventList;
            $resp['error'] = 0;
            $resp['success'] = 1;
        }

        return $resp;
    }

    function showEvent(Request $request)
    {
        $resp = array();
        $event_id = $request->input('event_id');

        $event = Event::find($event_id);

        if ($event->save()) {
            $resp['msg'] = 'Event date';
            $resp['event'] = $event->getEventDetails();
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed getting event data';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function cancelEvent(Request $request)
    {
        $resp = array();
        $event_id = $request->input('event_id');


        $event = $event = Event::find($event_id);

        if ($event != null) {
            if ($event->is_cancelled == 1) {
                Log::info("Event " . $event->title . ' already cancelled');
                $resp['msg'] = 'Event already cancelled';
                $resp['error'] = 1;
                $resp['success'] = 0;
            } else {
                $event->is_cancelled = 1;

                if ($event->save()) {
                    Log::info("Cancelled event " . $event->title);
                    $resp['msg'] = 'Event cancelled';
                    $resp['error'] = 0;
                    $resp['success'] = 1;

                    $this->sendCancelEventEmail($event);

                } else {
                    Log::debug('Failed cancelling event ' . $event->title);
                    $resp['msg'] = 'Failed cancelling event';
                    $resp['error'] = 2;
                    $resp['success'] = 0;
                }
            }

        } else {
            Log::debug('Event with id ' . $event_id . ' not found');
            $resp['msg'] = 'Event not found';
            $resp['error'] = 3;
            $resp['success'] = 0;
        }

        return $resp;

    }

    function delete(Request $request)
    {
        $resp = array();

        $id = $request->input('id');

        $event = Event::find($id);

        if ($event->delete()) {
            $resp['msg'] = 'Event deleted';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Delete failed';
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }


    private function sendEventEmail($event)
    {
        ReminderCreatedEmailJob::dispatch($event)
            ->delay(now()
                ->addSeconds(5));

        ReminderReceivedConfirmationJob::dispatch($event)
            ->delay(now()
                ->addSeconds(5));
    }

    private function sendCancelEventEmail($event)
    {
        $emailHandler = EmailHandlerFactory::createEmailHandler();
        $emailHandler->sendCancelReminderEmail($event);
    }

    private function getProperDateFromRange($myDate)
    {
        $date = date_create($myDate);
        $month = date_format($date, "m");

        if ($month == 0 || $month == 12) {
            $month = 12;
            $formattedActualDate = $date->format('Y-')
                . $month
                . $date->format('-d ')
                . $date->format('H:i');
        } else {
            $formattedActualDate = $myDate;
        }

        return $formattedActualDate;
    }
}
