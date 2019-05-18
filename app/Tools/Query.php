<?php

namespace App\Tools;

use App\Event;
use Illuminate\Support\Facades\Auth;

class Query
{
    // +++ GENERAL INFORMATION +++
    // The getUserEvents...() methods usually exclude rejected events. This behavior can be changed via the parameter $bIncludeRejected.


    // retrieve the next events for the current user - this includes currently ongoing events
    public static function getUserEventsNext($bIncludeRejected = false)
    {
        return self::getUserEventsAll($bIncludeRejected)->where('end_time', '>=', date('Y-m-d H:i'));
    }

    // retrieve the events for the current user within a specific month
    public static function getUserEventsMonth($year, $month, $bIncludeRejected = false)
    {
        // TODO use parameters
        return self::getUserEventsAll($bIncludeRejected);
    }

    // retrieve the events for the current user within a specific week
    public static function getUserEventsWeek($year, $week, $bIncludeRejected = false)
    {
        // TODO use parameters
        return self::getUserEventsAll($bIncludeRejected);
    }

    // retrieve the events for the current user within a specific day
    public static function getUserEventsDay($oDayBegin, $bIncludeRejected = false)
    {
        $oDayEnd = clone $oDayBegin;
        $oDayEnd->modify('+1 day');

        // get all events with a start time before the end of the day and an end time after the begin of the day
        return self::getUserEventsAll($bIncludeRejected)->where('start_time', '<=', Date::formatUTC($oDayEnd, 'Y-m-d H:i'))
                                                        ->where('end_time',   '>', Date::formatUTC($oDayBegin, 'Y-m-d H:i'));

    }

    // retrieve all events for the current user - future, present and past
    public static function getUserEvents($bIncludeRejected = false)
    {
        return self::getUserEventsAll($bIncludeRejected);
    }

    // Retrieves all events for the current user, this includes the following events with status tentative / accepted / no reply:
    //  - private events
    //  - memberships
    //  - subscriptions
    // Caution: This is an internal function so that the data can be further filtered. If you need an array of all events for one user, use getUserEvents().
    private static function getUserEventsAll($bIncludeRejected = false)
    {

        // return all private events
        $events = Event::where(function ($query) {

            $query->whereNull('group_id')->where('created_by', Auth::user()->id)
                // combined with all subscriptions
                ->orWhere(function ($query) {
                    $query->whereHas('group', function ($query) {
                        $query->where('public', true)->whereHas('subscribers', function ($query) {
                            $query->where('id', Auth::user()->id);
                        });
                    });
                })
                // combined with all memberships
                ->orWhere(function ($query) {
                    $query->whereHas('group', function ($query) {
                        $query->whereHas('members', function ($query) {
                            $query->where('id', Auth::user()->id);
                        });
                    });
                });
        })->orderBy('start_time');

        // filter out rejected events (if desired)
        if($bIncludeRejected)
            return $events;
        else
            return self::filterRejected($events);
    }

    // Filter for removing rejected events
    private static function filterRejected($data)
    {
        return $data->whereDoesntHave('replies', function ($query) {
            $query->where('status', Event::STATUS_REJECTED);
        });
    }
}