<?php

namespace App\Tools;

use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Auth;


class Date
{

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // parseFromInput
    //
    // This function translates a given date & time from the user's time zone into a UTC timestamp,
    // so that it can be easily stored in the database.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function parseFromInput($sDate, $sTime = '00:00', $sTimezone = null)
    {

        try {
            $dateTime = self::createFromInput($sDate, $sTime, $sTimezone);

            if ($dateTime != null) {
                $dateTime->setTimezone(new DateTimeZone('UTC'));
                return $dateTime->format('Y-m-d H:i:s');
            }
            return null;

        } catch (Exception $e) {
            // the input could not be parsed, probably due to invalid format or non-existent date
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // createFromInput
    //
    // This function translates a given date & time from the user's time zone into a DateTime object.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function createFromInput($sDate, $sTime = '00:00', $sTimezone = null)
    {
        // take the specified timezone if given, otherwise take the user's timezone as default
        $sTimezone = $sTimezone ?? self::getDefaultTimezone();

        try {
            // try to instantiate a new datetime object that takes the local time zone into account
            // this might fail, if the date or time specified did not match the right format
            $dateTime = new DateTime($sDate . ' ' . $sTime, new DateTimeZone($sTimezone));
            return $dateTime;

        } catch (Exception $e) {
            // the input could not be parsed, probably due to invalid format or non-existent date
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // createFromYMD
    //
    // This function translates a given date from the user's time zone into a DateTime object.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function createFromYMD(int $iYear, int $iMonth, int $iDay, $sTimezone = null, $sTime = '00:00')
    {
        $iYear  = intval($iYear);
        $iMonth = intval($iMonth);
        $iDay   = intval($iDay);

        if ($iYear > 0 && $iMonth > 0 && $iDay > 0) {
            return self::createFromInput("$iYear-$iMonth-$iDay", $sTime, $sTimezone);
        }
        return null;
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // createFromToday
    //
    // This function creates a DateTime object for the current day
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function createFromToday()
    {
        try {
            // try to instantiate a new datetime object, this might fail due to any strange circumstances
            $oDateTime = new DateTime('now', new DateTimeZone('UTC'));
            $oDateTime->setTime(0, 0);
            return $oDateTime;
        } catch (Exception $e) {
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // createFromFirstDayOfCurrentMonth
    //
    // This function creates a DateTime object for first day of the current month
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function createFromFirstDayOfMonth($iYear, $iMonth, $sTimezone = null)
    {
        return self::createFromYMD($iYear, $iMonth, 1, $sTimezone);
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toUserOutput
    //
    // This function translates a given UTC timestamp to the user's time zone
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toUserOutput($sTimestamp, $sFormat = 'd/m/Y H:i', $sTimezone = null)
    {

        try {
            // try to instantiate a new datetime object that takes the local time zone into account
            $oDateTime = new DateTime($sTimestamp, new DateTimeZone('UTC'));
            return self::format($oDateTime, $sFormat, $sTimezone);
        } catch (Exception $e) {
            // an error occurred, this should usually not be the case here...
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // format
    //
    // This function translates a given DateTime object to the user's time zone
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function format(DateTime $oDateTime, $sFormat, $sTimezone = null)
    {

        // take the specified timezone if given, otherwise take the user's timezone as default
        $sTimezone = $sTimezone ?? self::getDefaultTimezone();

        try {
            // try to instantiate a new datetime object that takes the local time zone into account
            $oDateTime->setTimezone(new DateTimeZone($sTimezone));
            return $oDateTime->format($sFormat);
        } catch (Exception $e) {
            // an error occurred, this should usually not be the case here...
            return null;
        }

    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // formatUTC
    //
    // This function translates a given DateTime object to UTC for database comparison
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function formatUTC(DateTime $oDateTime, $sFormat = 'Y-m-d H:i')
    {
        return self::format($oDateTime, $sFormat, 'UTC');
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toDateAndTime
    //
    // This function translates a given UTC timestamp date and time in the user's time zone
    // The values are set by reference.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toDateAndTime($sTimestamp, &$sDate, &$sTime, $sTimezone = null)
    {
        $sDate = self::toUserOutput($sTimestamp, 'Y-m-d', $sTimezone);
        $sTime = self::toUserOutput($sTimestamp, 'H:i', $sTimezone);
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // getDefaultTimezone
    //
    // This function retrieves the default timezone, depending on whether the user is logged in or not
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function getDefaultTimezone()
    {
        if (Auth::check()) {
            return Auth::user()->timezone;
        } else {
            return 'UTC';
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toAssocArray
    //
    // This function formats a given DateTime object into an assocaative array.
    // This can be used for user interface / link generation.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toAssocArray(DateTime $oDateTime)
    {
        return array(
            'year'  => self::format($oDateTime, 'Y'),
            'month' => self::format($oDateTime, 'm'),
            'day'   => self::format($oDateTime, 'd'),
            'week'  => self::format($oDateTime, 'W'),
        );
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // getNumDaysInMonth
    //
    // Returns the number of days in the current month
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function getNumDaysInMonth(DateTime $oDateTime)
    {
        return intval(self::format($oDateTime, 't'));
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // getDayOfWeek
    //
    // Returns the day of week for the given date, from 1 = Monday to 7 = Sunday
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function getDayOfWeek(DateTime $oDateTime)
    {
        return intval(self::format($oDateTime, 'N'));
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // modify
    //
    // This function adds a certain interval to a DateTime object
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function modify(DateTime $oDateTime, $sInterval)
    {
        $oNewTime = clone $oDateTime;
        $oNewTime->modify($sInterval);
        return $oNewTime;
    }

    public static function isSameDate($start, $end)
    {
        $startDate = self::toUserOutput($start, 'Y-m-d');
        $endDate   = self::toUserOutput($end, 'Y-m-d');

        if ($startDate == $endDate) {
            return true;
        }
        return false;
    }
}
