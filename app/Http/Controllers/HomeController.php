<?php

namespace App\Http\Controllers;

use App\Tools\Date;
use App\Tools\Permission;
use App\Tools\PermissionFactory;
use App\Tools\Query;
use DateInterval;

class HomeController extends Controller
{

    const TYPE_NEXT = "next";
    const TYPE_MONTH = "month";
    const TYPE_WEEK = "week";
    const TYPE_DAY = "day";

    public function index()
    {
        // default view can be chosen here
        return $this->month();
    }

    public function next()
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();
        
        // get all events for this user
        $events = Query::getUserEventsNext()->get();

        return view('calendars.next', ['events' => $events, 'type' => self::TYPE_NEXT]);
    }

    public function month($year = 0, $month = 0)
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        $oDay = null;

        // take given year and month (if specified)
        if ($year > 0  && $month > 0) {
            $oDay = Date::createFromFirstDayOfMonth($year, $month);
        }

        // use current month if nothing is specified or invalid date was given (i.e. function call above failed and returned null)
        if ($oDay == null) {
            $aTodayInfo = Date::toAssocArray(Date::createFromToday());
            $oDay = Date::createFromFirstDayOfMonth($aTodayInfo['year'], $aTodayInfo['month']);
        }

        // get all events for this user
        $aDays = Query::getUserEventsMonth($oDay);

        // calculate previous and next day for navigation
        $aPrev = Date::toAssocArray(Date::modify($oDay, '-1 month'));
        $aNext = Date::toAssocArray(Date::modify($oDay, '+1 month'));

        return view('calendars.month', ['days' => $aDays, 'type' => self::TYPE_MONTH, 'month' => Date::format($oDay, 'M Y'), 'prev' => $aPrev, 'next' => $aNext, 'date' => Date::toAssocArray($oDay)]);
    }

    public function week($year = 0, $week = 0) // TODO implement default value (timezone!)
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $events = Query::getUserEventsWeek($year, $week)->get();

        return view('calendars.week', ['events' => $events, 'type' => self::TYPE_WEEK]);
    }

    public function day($year = 0, $month = 0, $day = 0) // TODO implement default value (timezone!)
    {
        $oDay = Date::createFromYMD($year, $month, $day);
        if ($oDay == null) {
            $oDay = Date::createFromToday();
        }

        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $events = Query::getUserEventsDay($oDay)->get();

        // calculate previous and next day for navigation
        $aPrev = Date::toAssocArray(Date::modify($oDay, '-1 day'));
        $aNext = Date::toAssocArray(Date::modify($oDay, '+1 day'));

        return view('calendars.day', ['events' => $events, 'type' => self::TYPE_DAY, 'day' => Date::format($oDay, 'l, M j Y'), 'prev' => $aPrev, 'next' => $aNext]);
    }
}
