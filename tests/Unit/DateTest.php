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
    }

    public function testToDateAndTime()
    {

    }
}
