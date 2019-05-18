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
        $events = self::getUserEventsAll()->whereDate('end_time', '>=', date('Y-m-d H:i'));
        if (!$bIncludeRejected) {
            return self::filterRejected($events);
        }
        return $events;
    }

    // retrieve the events for the current user within a specific month
    public static function getUserEventsMonth($year, $month, $bIncludeRejected = false)
    {
        // TODO use parameters
        $events = self::getUserEventsAll();
        if (!$bIncludeRejected) {
            return self::filterRejected($events);
        }
        return $events;
    }

    // retrieve the events for the current user within a specific week
    public static function getUserEventsWeek($year, $week, $bIncludeRejected = false)
    {
        // TODO use parameters
        $events = self::getUserEventsAll();
        if (!$bIncludeRejected) {
            return self::filterRejected($events);
        }
        return $events;
    }

    // retrieve the events for the current user within a specific day
    public static function getUserEventsDay($year, $day, $bIncludeRejected = false)
    {
        // TODO use parameters
        $events = self::getUserEventsAll();
        if (!$bIncludeRejected) {
            return self::filterRejected($events);
        }
        return $events;
    }

    // retrieve all events for the current user - future, present and past
    public static function getUserEvents($bIncludeRejected = false)
    {
        $events = self::getUserEventsAll();
        if (!$bIncludeRejected) {
            return self::filterRejected($events);
        }
        return $events;
    }

    // Retrieves all events for the current user, this includes the following events with status tentative / accepted / no reply:
    //  - private events
    //  - memberships
    //  - subscriptions
    // Caution: This is an internal function so that the data can be further filtered. If you need an array of all events for one user, use getUserEvents().
    private static function getUserEventsAll()
    {

        // return all private events
        return Event::whereNull('group_id')->where('created_by', Auth::user()->id)
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
            })->orderBy('start_time');
    }

    // Filter for removing rejected events
    private static function filterRejected($data)
    {
        return $data->whereDoesntHave('replies', function ($query) {
            $query->where('status', Event::STATUS_REJECTED);
        });
    }
}