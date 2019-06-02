<?php

namespace App\Tools;

use App\Event;
use App\Group;
use DateTime;
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
    // see comment in function below for return value
    public static function getUserEventsMonth($oDay, $bIncludeRejected = false)
    {
        // get all events for the requested month
        $aDateInfo = Date::toAssocArray($oDay);


        // create date for start end end of month
        $oMonthBegin  = Date::createFromYMD($aDateInfo['year'], $aDateInfo['month'], 1, null, '00:00');
        $iDaysInMonth = Date::getNumDaysInMonth($oMonthBegin);

        $oMonthEnd = Date::createFromYMD($aDateInfo['year'], $aDateInfo['month'], $iDaysInMonth, null, '23:59');
        $events    = self::getUserEventsAll($bIncludeRejected)->where('start_time', '<=', Date::formatUTC($oMonthEnd))
            ->where('end_time', '>', Date::formatUTC($oMonthBegin))->get();

        $iDayOfWeek = Date::getDayOfWeek($oMonthBegin);

        // create an array of all days (index, 1-based!!!) containing the following values:
        // dayOfWeek -> 1 for  monday to 7 for sunday
        // events    -> assoc array containing information about the events for this day:
        //      id    => event id
        //      name  => the name of the event

        $aDays = [];

        // add general event information (events are added later on to improve time complexity)
        for ($i = 1; $i <= $iDaysInMonth; $i++) {

            // create array entry and add day of week
            $aDays[$i] = [
                'dayOfWeek' => $iDayOfWeek,
                'events'    => [],
            ];

            // increment day of week
            $iDayOfWeek++;
            if ($iDayOfWeek > 7)
                $iDayOfWeek = 1;
        }

        // add events to the corresponding days
        foreach ($events as $e) {

            $oStartTime = new DateTime($e->start_time);
            $oEndTime   = new DateTime($e->end_time);

            // first, determine effective start and end day -> handle events that begin before this month or end after this month
            $oMin = $oStartTime < $oMonthBegin ? $oMonthBegin : $oStartTime;
            $oMax = $oEndTime > $oMonthEnd ? $oMonthEnd : $oEndTime;

            // get the day of month from the DateTime objects
            $iMin = intval(Date::format($oMin, 'j'));
            $iMax = intval(Date::format($oMax, 'j'));

            // iterate over all days affected by the event and add it to their 'events' entry

            for($k = $iMin; $k <= $iMax; $k++) {
                $aDays[$k]['events'][] = ['id' => $e->id, 'name' => $e->name, 'status' => $e->myReply()];

            }
        }

        return $aDays;
    }

    // retrieve the events for the current user within a specific day
    public static function getUserEventsDay($oDayBegin, $bIncludeRejected = false)
    {
        $oDayEnd = clone $oDayBegin;
        $oDayEnd->modify('+1 day');

        // get all events with a start time before the end of the day and an end time after the begin of the day
        return self::getUserEventsAll($bIncludeRejected)->where('start_time', '<', Date::formatUTC($oDayEnd))
            ->where('end_time', '>', Date::formatUTC($oDayBegin));

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

        $oEvents = Event::where(function ($query) {

            // return all private events
            $query->whereNull('group_id')->where('created_by', Auth::id())
                // combined with all subscriptions
                ->orWhere(function ($query) {
                    $query->whereHas('group', function ($query) {
                        $query->where('public', true)->whereHas('subscribers', function ($query) {
                            $query->where('id', Auth::id());
                        });
                    });
                })
                // combined with all memberships
                ->orWhere(function ($query) {
                    $query->whereHas('group', function ($query) {
                        $query->whereHas('members', function ($query) {
                            $query->where('id', Auth::id());
                        });
                    });
                });
        })->orderBy('start_time');

        // filter out rejected events (if desired)
        if ($bIncludeRejected)
            return $oEvents;
        else
            return self::filterRejected($oEvents);
    }

    // Filter for removing rejected events
    private static function filterRejected($data)
    {
        return $data->whereDoesntHave('replies', function ($query) {

            $query->where('event_replies.status', '=', Event::STATUS_REJECTED)->where('id', '=', Auth::id());
        });

    }

    // Retrieve all messages for a given event with a message id GREATER OR EQUAL to the given one
    // This method qualifies for being used with automatic message updates (only new messages are loaded.)
    public static function getNewMessagesForEvent($iEventId, $iMessageId = 0)
    {
        return Event::findOrFail($iEventId)->messages()->where('id', '>=', intval($iMessageId))->orderBy('id')->get();
    }

    // Retrieve all groups a user can access. This includes public groups and memberships
    public static function getAllAccessibleGroups()
    {
        return Group::where(function($group) {
            $group->where('public', true)->orWhereHas('members',
                function ($query) {
                    $query->where('id', Auth::id());
            });
        });
    }
    
    // Retrieve all events a user can access. This includes public events, memberships and private events
    public static function getAllAccessibleEvents()
    {
        return Event::where(function($event) {
            $event->whereHas('group', function($query) {
                $query->whereIn('id', Query::getAllAccessibleGroups()->pluck('id')->toArray());
            })->orWhere(function ($query) {
                $query->whereNull('group_id')->where('created_by', Auth::id());
            });
        });
    }

    // Retrieve all events of the logged in user without a reply
    public static function getNotifications()
    {
        return Query::getUserEventsNext()->whereHas('group')->whereDoesntHave('replies', function ($q) {
            $q->where('id', '=', Auth::id());
        })->get();
    }

    // Get the number of the events of the logged in user without a reply
    public static function getMessageCount()
    {
        $notifications = self::getNotifications();
        return count($notifications);
    }

}