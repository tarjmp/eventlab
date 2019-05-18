<?php

namespace App\Http\Controllers;

use App\Tools\Permission;
use App\Tools\PermissionFactory;
use App\Tools\Query;

class HomeController extends Controller
{

    const TYPE_NEXT = "next";
    const TYPE_MONTH = "month";
    const TYPE_WEEK = "week";
    const TYPE_DAY = "day";

    public function index()
    {
        // default view can be chosen here
        return $this->next();
    }

    public function next()
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();
        
        // get all events for this user
        $events = Query::getUserEventsNext()->get();

        return view('calendars.next', ['events' => $events, 'type' => self::TYPE_NEXT]);
    }

    public function month($year = 0, $month = 0) // TODO implement default value (timezone!)
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $events = Query::getUserEventsMonth($year, $month)->get();

        return view('calendars.month', ['events' => $events, 'type' => self::TYPE_MONTH]);
    }

    public function week($year = 0, $week = 0) // TODO implement default value (timezone!)
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $events = Query::getUserEventsWeek($year, $week)->get();

        return view('calendars.week', ['events' => $events, 'type' => self::TYPE_WEEK]);
    }

    public function day($year = 0, $day = 0) // TODO implement default value (timezone!)
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $events = Query::getUserEventsDay($year, $day)->get();

        return view('calendars.day', ['events' => $events, 'type' => self::TYPE_DAY]);
    }
}
