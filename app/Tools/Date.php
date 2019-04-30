<?php

namespace App\Tools;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;


class Date {

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // parseFromInput
    //
    // This function translates a given date & time from the user's time zone into a UTC timestamp,
    // so that it can be easily stored in the database.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function parseFromInput($sDate, $sTime, $sTimezone = null) {

        // take the specified timezone if given, otherwise take the user's timezone as default
        $sTimezone = $sTimezone ?? self::getDefaultTimezone();

        try {
            // try to instantiate a new datetime object that takes the local time zone into account
            // this might fail, if the date or time specified did not match the right format
            $dateTime = new DateTime($sDate . ' ' . $sTime, new DateTimeZone($sTimezone));
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            return $dateTime->format('Y-m-d H:i:s');

        } catch (\Exception $e) {
            // the input could not be parsed, probably due to invalid format or non-existent date
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toUserOutput
    //
    // This function translates a given UTC timestamp to the user's time zone
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toUserOutput($sTimestamp, $sFormat = 'd/m/Y H:i', $sTimezone = null) {

        // take the specified timezone if given, otherwise take the user's timezone as default
        $sTimezone = $sTimezone ?? self::getDefaultTimezone();

        try {
            // try to instantiate a new datetime object that takes the local time zone into account
            $dateTime = new DateTime($sTimestamp, new DateTimeZone('UTC'));
            $dateTime->setTimezone(new DateTimeZone($sTimezone));
            return $dateTime->format($sFormat);

        } catch (\Exception $e) {
            // an error occurred, this should usually not be the case here...
            return null;
        }
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // toDateAndTime
    //
    // This function translates a given UTC timestamp date and time in the user's time zone
    // The values are set by reference.
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    static function toDateAndTime($sTimestamp, &$sDate, &$sTime, $sTimezone = null) {
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
}
