<?php

namespace Tests\Unit;

use App\Tools\CustomDateTime;
use Tests\TestCase;

class CustomDateTimeTest extends TestCase
{
    /**
     * Test for Class 'CustomDateTime'
     *
     * @return void
     */
    public function testDate()
    {
        $this->loginWithFakeUser();

        // TEST 1: valid timestamp and timezone
        $validTimestampTimezone = new CustomDateTime('2019-03-30 17:00', 'UTC');
        $this->assertEquals('2019-03-30', $validTimestampTimezone->date());

        $validTimestampTimezone = new CustomDateTime('2019-04-30 10:00', 'EAT');
        $this->assertEquals('2019-04-30', $validTimestampTimezone->date());

        $validTimestampTimezone = new CustomDateTime('2019-01-02 01:00', 'America/Buenos_Aires');
        $this->assertEquals('2019-01-01', $validTimestampTimezone->date());


        // TEST 2: valid timestamp without timezone
        $validTimestamp = new CustomDateTime('2019-01-30 09:00');
        $this->assertEquals('2019-01-30', $validTimestamp->date());


        // TEST 3: invalid timestamp
        $invalidTimestamp = new CustomDateTime('2019-01-33 09:00', 'UTC');
        $this->assertNull($invalidTimestamp->date());


        // TEST 4: invalid timezone
        $invalidTimezone = new CustomDateTime('2019-05-01 14:00', 'XYZ');
        $this->assertNull($invalidTimezone->date());


    }

    public function testTime() {

        $this->loginWithFakeUser();

        // TEST 1: valid timestamp and timezone
        $validTimestampTimezone = new CustomDateTime('2019-03-30 17:00', 'UTC');
        $this->assertEquals('17:00', $validTimestampTimezone->time());

        $validTimestampTimezone = new CustomDateTime('2019-04-30 10:00', 'EAT');
        $this->assertEquals('13:00', $validTimestampTimezone->time());

        $validTimestampTimezone = new CustomDateTime('2019-01-02 01:00', 'America/Buenos_Aires');
        $this->assertEquals('22:00', $validTimestampTimezone->time());


        // TEST 2: valid timestamp without timezone
        $validTimestamp = new CustomDateTime('2019-01-30 09:00');
        $this->assertEquals('09:00', $validTimestamp->time());


        // TEST 3: invalid timestamp
        $invalidTimestamp = new CustomDateTime('2019-01-33 09:00', 'UTC');
        $this->assertNull($invalidTimestamp->time());


        // TEST 4: invalid timezone
        $invalidTimezone = new CustomDateTime('2019-05-01 14:00', 'XYZ');
        $this->assertNull($invalidTimezone->time());

    }
}
