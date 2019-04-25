<?php

namespace Tests\Unit;

use App\Rules\DateTimeValidation;
use Tests\TestCase;

class DateTimeValidationTest extends TestCase
{
    /**
     *  Test date and time validation
     */
    public function testPasses()
    {

        $this->loginWithFakeUser();

        // valid date format is yyyy-mm-dd, according to:
        // https://www.w3.org/TR/2011/WD-html-markup-20110405/input.date.html

        // TEST 1: valid date
        $validDate = new DateTimeValidation('2019-12-10');

        // TEST 1A: valid times
        $this->assertTrue($validDate->passes('dummy', '15:00:00'));
        $this->assertTrue($validDate->passes('dummy', '12:59:00'));
        $this->assertTrue($validDate->passes('dummy', '00:00:00'));
        $this->assertTrue($validDate->passes('dummy', '02:22:00'));

        // TEST 1B: invalid times
        $this->assertNotTrue($validDate->passes('dummy', 'banana'));
        $this->assertNotTrue($validDate->passes('dummy', '2-2'));
        $this->assertNotTrue($validDate->passes('dummy', '23:70:00'));
        $this->assertNotTrue($validDate->passes('dummy', '-3:15:00'));
        $this->assertNotTrue($validDate->passes('dummy', '31:15:00'));

        // TEST 2: invalid date
        $invalidDate = new DateTimeValidation('2019-17-10');

        // TEST 2A: valid times
        $this->assertNotTrue($invalidDate->passes('dummy', '15:00:00'));
        $this->assertNotTrue($invalidDate->passes('dummy', '12:59:00'));
        $this->assertNotTrue($invalidDate->passes('dummy', '00:00:00'));
        $this->assertNotTrue($invalidDate->passes('dummy', '02:22:00'));

        // TEST 2B: invalid times
        $this->assertNotTrue($invalidDate->passes('dummy', 'banana'));
        $this->assertNotTrue($invalidDate->passes('dummy', '2-2'));
        $this->assertNotTrue($invalidDate->passes('dummy', '23:70:00'));
        $this->assertNotTrue($invalidDate->passes('dummy', '-3:15:00'));
        $this->assertNotTrue($invalidDate->passes('dummy', '31:15:00'));
    }

    public function testMessage() {
        // expect a string as return value
        $invalidDate = new DateTimeValidation('2019-17-10');
        $this->assertTrue(is_string($invalidDate->message()));
    }
}
