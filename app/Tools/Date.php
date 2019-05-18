<?php

namespace App\Tools;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Auth;


class Date {

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // parseFromInput
    //
    // This function translates a given date & time from the user's time zone into a UTC timestamp,
    // so that it can be easily stored in the database.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function parseFromInput(string $sDate, string $sTime = '00:00', string $sTimezone = null) {

        try {
            $dateTime = self::createFromInput($sDate, $sTime, $sTimezone);
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            return $dateTime->format('Y-m-d H:i:s');

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
    public static function createFromInput(string $sDate, string $sTime = '00:00', string $sTimezone = null)
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
    public static function createFromYMD(int $iYear, int $iMonth, int $iDay, string $sTimezone = null)
    {
        $iYear = intval($iYear);
        $iMonth = intval($iMonth);
        $iDay = intval($iDay);

        if($iYear > 0 && $iMonth > 0 &&  $iDay > 0) {
            return self::createFromInput( "$iYear-$iMonth-$iDay", '00:00', $sTimezone);
        }
        return null;
    }

    public static function createFromNow() {
        try {
            // try to instantiate a new datetime object, this might fail due to any strange circumstances
            return  new DateTime('now', new DateTimeZone('UTC'));
        } catch (Exception $e) {
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toUserOutput
    //
    // This function translates a given UTC timestamp to the user's time zone
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toUserOutput(string $sTimestamp, string $sFormat = 'd/m/Y H:i', string $sTimezone = null) {

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
    static function format(DateTime $oDateTime, string $sFormat, string $sTimezone = null) {

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
    static function formatUTC(DateTime $oDateTime, string $sFormat)
    {
        return self::format($oDateTime, $sFormat, 'UTC');
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toDateAndTime
    //
    // This function translates a given UTC timestamp date and time in the user's time zone
    // The values are set by reference.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toDateAndTime(string $sTimestamp, string &$sDate, string &$sTime, string $sTimezone = null) {
        $sDate = self::toUserOutput($sTimestamp, 'Y-m-d', $sTimezone);
        $sTime = self::toUserOutput($sTimestamp, 'H:i', $sTimezone);
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // getDefaultTimezone
    //
    // This function retrieves the default timezone, depending on whether the user is logged in or not
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function getDefaultTimezone() {
        if(Auth::check()) {
            return Auth::user()->timezone;
        }
        else {
            return 'UTC';
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toAssocArray
    //
    // This function formats a given DateTime object into an assocaative array.
    // This can be used for user interface / link generation.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toAssocArray(DateTime $oDateTime) {
        return array(
            'year'  => self::format($oDateTime, 'Y'),
            'month' => self::format($oDateTime, 'm'),
            'day'   => self::format($oDateTime, 'd'),
            'week'  => self::format($oDateTime, 'W'),
        );
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // addInterval
    //
    // This function adds a certain interval to a DateTime object
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function addInterval(DateTime $oDateTime, string $sInterval)
    {
        $oNewTime = clone $oDateTime;
        $oNewTime->add(new DateInterval($sInterval));
        return $oNewTime;
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // subInterval
    //
    // This function subtracts a certain interval from a DateTime object
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public static function subInterval(DateTime $oDateTime, string $sInterval)
    {
        $oNewTime = clone $oDateTime;
        $oNewTime->sub(new DateInterval($sInterval));
        return $oNewTime;
    }
}
