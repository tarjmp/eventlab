<?php

namespace App\Http\Controllers;

use App\Event;
use App\Tools\Permission;
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
        Permission::check(Permission::showHomeCalendar);

        // get all events for this user
        $events = Query::getUserEventsNext()->get();

        return view('home', ['events' => $events, 'type' => self::TYPE_NEXT]);
    }

    public function month($year, $month)
    {
        // require home screen permission
        Permission::check(Permission::showHomeCalendar);

        // get all events for this user
        $events = Query::getUserEventsMonth($year, $month)->get();

        return view('home', ['events' => $events, 'type' => self::TYPE_MONTH]);
    }

    public function week($year, $week)
    {
        // require home screen permission
        Permission::check(Permission::showHomeCalendar);

        // get all events for this user
        $events = Query::getUserEventsWeek($year, $week)->get();

        return view('home', ['events' => $events, 'type' => self::TYPE_WEEK]);
    }

    public function day($year, $day)
    {
        // require home screen permission
        Permission::check(Permission::showHomeCalendar);

        // get all events for this user
        $events = Query::getUserEventsDay($year, $day)->get();

        return view('home', ['events' => $events, 'type' => self::TYPE_DAY]);
    }
}
