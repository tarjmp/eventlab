<?php

namespace App\Http\Controllers;

use App\Event;
use App\Group;
use App\Tools\Date;
use App\Tools\PermissionFactory;
use App\Tools\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    const TYPE_NEXT = "next";
    const TYPE_MONTH = "month";
    const TYPE_WEEK = "week";
    const TYPE_DAY = "day";

    const SHOW_REJECTED_EVENTS = 'show-rejected';

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
        $cEvents = Query::getUserEventsNext($this->showRejectedEvents())->get();

        return view('calendars.next', ['events' => $cEvents, 'type' => self::TYPE_NEXT]);
    }

    public function month($year = 0, $month = 0)
    {
        if (Auth::guest()) {
            return $this->monthGuest($year, $month);
        } else {
            return $this->monthLoggedIn($year, $month);
        }
    }

    public function day($year = 0, $month = 0, $day = 0)
    {
        $oDay = Date::createFromYMD($year, $month, $day);
        if ($oDay == null) {
            $oDay = Date::createFromToday();
        }

        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $cEvents = Query::getUserEventsDay($oDay, $this->showRejectedEvents())->get();

        // calculate previous and next day for navigation
        $aPrev = Date::toAssocArray(Date::modify($oDay, '-1 day'));
        $aNext = Date::toAssocArray(Date::modify($oDay, '+1 day'));

        return view('calendars.day', ['events' => $cEvents, 'type' => self::TYPE_DAY, 'day' => Date::format($oDay, 'l, M j Y'), 'prev' => $aPrev, 'next' => $aNext]);
    }

    // Function to show / hide rejected events in the calendar view
    public function toggleRejected()
    {

        PermissionFactory::createShowHomeCalendar()->check();

        // toggle parameter
        session([self::SHOW_REJECTED_EVENTS => !$this->showRejectedEvents()]);

        // redirect to last page
        return redirect()->back();
    }

    private function showRejectedEvents()
    {
        return session(self::SHOW_REJECTED_EVENTS);
    }

    public function publicGroup()
    {
        $groups = Group::where('public', true)->get();

        return view('public-group')->with(['groups' => $groups]);
    }

    public function showGroup(Request $request, $year = 0, $month = 0)
    {
        // validate the incoming request
        $request->validate(['members' => 'required|array|min:1']);

        $data = $request->all();

        //Check if only public groups were submitted
        foreach ($data['members'] as $member) {
            Group::findOrFail($member)->where('public', true);
        }

        $oDay = null;

        // take given year and month (if specified)
        if ($year > 0 && $month > 0) {
            $oDay = Date::createFromFirstDayOfMonth($year, $month);
        }

        // use current month if nothing is specified or invalid date was given (i.e. function call above failed and returned null)
        if ($oDay == null) {
            $aTodayInfo = Date::toAssocArray(Date::createFromToday());
            $oDay = Date::createFromFirstDayOfMonth($aTodayInfo['year'], $aTodayInfo['month']);
        }

        // get all public events
        $aDays = Query::getPublicEventsMonth($oDay, $data['members']);

        // calculate previous and next day for navigation
        $aPrev = Date::toAssocArray(Date::modify($oDay, '-1 month'));
        $aNext = Date::toAssocArray(Date::modify($oDay, '+1 month'));

        return view('calendars.month', ['days' => $aDays, 'type' => self::TYPE_MONTH, 'month' => Date::format($oDay, 'M Y'), 'prev' => $aPrev, 'next' => $aNext, 'date' => Date::toAssocArray($oDay), 'members' => $data['members']]);
    }

    private function monthCalculationODay($year, $month)
    {
        $oDay = null;
        // take given year and month (if specified)
        if ($year > 0 && $month > 0) {
            $oDay = Date::createFromFirstDayOfMonth($year, $month);
        }
        // use current month if nothing is specified or invalid date was given (i.e. function call above failed and returned null)
        if ($oDay == null) {
            $aTodayInfo = Date::toAssocArray(Date::createFromToday());
            $oDay = Date::createFromFirstDayOfMonth($aTodayInfo['year'], $aTodayInfo['month']);
        }
        return $oDay;
    }

    private function monthCalculationView($oDay, $aDays)
    {
        // calculate previous and next day for navigation
        $aPrev = Date::toAssocArray(Date::modify($oDay, '-1 month'));
        $aNext = Date::toAssocArray(Date::modify($oDay, '+1 month'));
        return view('calendars.month', ['days' => $aDays, 'type' => self::TYPE_MONTH, 'month' => Date::format($oDay, 'M Y'), 'prev' => $aPrev, 'next' => $aNext, 'date' => Date::toAssocArray($oDay)]);

    }

    private function monthLoggedIn($year, $month)
    {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        $oDay = $this->monthCalculationODay($year, $month);
        // get all events for this user
        $aDays = Query::getUserEventsMonth($oDay, $this->showRejectedEvents());
        return $this->monthCalculationView($oDay, $aDays);
    }

    private function monthGuest($year, $month)
    {
        if (session('public_group') != null) {
            $oDay = $this->monthCalculationODay($year, $month);
            // get all events for this user
            $aDays = Query::getSessionEventsMonth($oDay);
            return $this->monthCalculationView($oDay, $aDays);
        } else {
            return redirect(route('groups'));
        }
    }

    public function selectGroup(Request $request)
    {
        $data = $request->all();

        session(['public_group' => $data['public_group']]);

        return redirect(route('home'));
    }
}
