<?php

namespace Tests\Unit;

use App\Tools\Date;
use Tests\TestCase;

class DateTest extends TestCase
{
    /**
     * Test Date Class
     */

    public function testParseFromInput()
    {
        //Needed to check getting the timezone from a users profile
        $this->loginWithFakeUser();

        //Invalid day
        $this->assertNull(Date::parseFromInput('2019-01-32', '05:00'));
        //Invalid month
        $this->assertNull(Date::parseFromInput('2019-13-02', '05:00'));
        //check for invalid time zone string
        $this->assertNull(Date::parseFromInput('2019-01-01', '10:01', 'XYZ'));

        //valid entries
        //Get the timezone from the users profile
        $this->assertEquals('2019-01-01 08:01:00', Date::parseFromInput('2019-01-01', '08:01'));
        //check with submitted timezone
        $this->assertEquals('2019-01-01 18:01:00', Date::parseFromInput('2019-01-01', '10:01', 'PST'));
    }

    public function testToUserOutput()
    {
        //Needed to check getting the timezone from a users profile
        $this->loginWithFakeUser();

        //valid entries
        $this->assertEquals('31/12/2019 08:05', Date::toUserOutput('2019-12-31 08:05'));
        $this->assertEquals('2019/12/31 08:05', Date::toUserOutput('2019-12-31 08:05', 'Y/m/d H:i'));
        $this->assertEquals('2019/12/31 08:05', Date::toUserOutput('2019-12-31 16:05', 'Y/m/d H:i', 'PST'));
        //invalid entries day
        $this->assertNull(Date::toUserOutput('2019-12-32 08:05'));
        $this->assertNull(Date::toUserOutput('2019-12-32 08:05', 'Y/m/d H:i'));
        $this->assertNull(Date::toUserOutput('2019-12-32 08:05', 'Y/m/d H:i', 'PST'));
        //invalid entries month
        $this->assertNull(Date::toUserOutput('2019-13-15 08:05'));
        $this->assertNull(Date::toUserOutput('2019-13-15 08:05', 'Y/m/d H:i'));
        $this->assertNull(Date::toUserOutput('2019-13-15 08:05', 'Y/m/d H:i', 'PST'));
        //invalid entries hour
        $this->assertNull(Date::toUserOutput('2019-12-31 25:05'));
        $this->assertNull(Date::toUserOutput('2019-12-31 25:05', 'Y/m/d H:i'));
        $this->assertNull(Date::toUserOutput('2019-12-31 25:05', 'Y/m/d H:i', 'PST'));
        //invalid entries minute
        $this->assertNull(Date::toUserOutput('2019-12-31 08:61'));
        $this->assertNull(Date::toUserOutput('2019-12-31 08:61', 'Y/m/d H:i'));
        $this->assertNull(Date::toUserOutput('2019-12-31 08:61', 'Y/m/d H:i', 'PST'));
        //invalid entry timezone
        $this->assertNull(Date::toUserOutput('2019-12-31 08:61', 'Y/m/d H:i', 'XYZ'));
    }

    public function testToDateAndTime()
    {
        //Needed to check getting the timezone from a users profile
        $this->loginWithFakeUser();

        //valid data
        Date::toDateAndTime('2019-12-31 16:05', $sDate, $sTime);
        $this->assertEquals('2019-12-31', $sDate);
        $this->assertEquals('16:05', $sTime);
        //valid data with timezone
        Date::toDateAndTime('2019-12-31 16:05', $sDate, $sTime, 'PST');
        $this->assertEquals('2019-12-31', $sDate);
        $this->assertEquals('08:05', $sTime);
        //invalid day
        Date::toDateAndTime('2019-12-32 16:05', $sDate, $sTime);
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid day with timezone
        Date::toDateAndTime('2019-12-32 16:05', $sDate, $sTime, 'PST');
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid month
        Date::toDateAndTime('2019-13-15 16:05', $sDate, $sTime);
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid month with timezone
        Date::toDateAndTime('2019-13-15 16:05', $sDate, $sTime, 'PST');
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid hour
        Date::toDateAndTime('2019-12-31 25:05', $sDate, $sTime);
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid hour with timezone
        Date::toDateAndTime('2019-12-32 25:05', $sDate, $sTime, 'PST');
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid minute
        Date::toDateAndTime('2019-13-15 16:61', $sDate, $sTime);
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid minute with timezone
        Date::toDateAndTime('2019-13-15 16:61', $sDate, $sTime, 'PST');
        $this->assertNull($sDate);
        $this->assertNull($sTime);
        //invalid timezone
        Date::toDateAndTime('2019-12-31 16:05', $sDate, $sTime, 'XYZ');
        $this->assertNull($sDate);
        $this->assertNull($sTime);
    }
}
