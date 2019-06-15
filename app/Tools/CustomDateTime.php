<?php

namespace App\Tools;


// This class represents a certain point in time containing date (year, month, day) and time (hours, minutes).
// It can easily be used to create a human-readable date and time from a UTC timestamp.
// Timezone conversion is included.

class CustomDateTime
{

    private $timezone;  // the timezone that will be used for output
    private $timestamp; // the datetime object containing the actual information about the point in time
    private $date;      // the string representation of the day in the specified timezone
    private $time;      // the string representation of the time in the specified timezone

    // The constructor takes a UTC timestamp and an optional timezone
    public function __construct($sTimestamp, $sTimezone = null)
    {
        $this->timestamp = $sTimestamp;
        $this->timezone  = $sTimezone;
        Date::toDateAndTime($this->timestamp, $this->date, $this->time, $this->timezone);
    }

    // The date() function returns the date of the point in time in format yyyy-mm-dd
    public function date()
    {
        return $this->date;
    }

    // The date() function returns the time of the datetime in format hh:mm
    public function time()
    {
        return $this->time;
    }
}