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

        //Invalid dates
        $this->assertNull(Date::parseFromInput('2019-01-32', '05:00'));
        $this->assertNull(Date::parseFromInput('2019-13-02', '05:00'));
        //Get the timezone from the users profile
        $this->assertEquals('2019-01-01 08:01:00', Date::parseFromInput('2019-01-01', '08:01'));
        //check with submitted timezone
        $this->assertEquals('2019-01-01 18:01:00', Date::parseFromInput('2019-01-01', '10:01', 'PST'));
    }

    public function testToUserOutput()
    {

    }

    public function testToDateAndTime()
    {

    }
}
