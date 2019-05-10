<?php

namespace Tests\Unit;

use App\Tools\Check;
use Tests\TestCase;

class CheckTest extends TestCase
{
    /**
     * A basic test to the 'Check' class.
     *
     * @return void
     */
    public function testIsLoggedIn()
    {
        // TEST 1: user is not logged in
        $this->assertFalse(Check::isLoggedIn());

        // TEST 2: user is logged in
        $this->loginWithDBUser(1);
        $this->assertTrue(Check::isLoggedIn());
    }

    public function testIsMemberOfGroup() {

        $this->loginWithDBUser(2);

        // TEST 1: user is member of a group
        $this->assertTrue(Check::isMemberOfGroup(2));

        // TEST 2: user is no member of a group
        $this->assertFalse(Check::isMemberOfGroup(11));
        $this->assertFalse(Check::isMemberOfGroup(17));
    }

    public function testIsMemberOfEvent() {

        $this->loginWithDBUser(2);

        // TEST 1: user is member of an event
        $this->assertTrue(Check::isMemberOfEvent(1));

        // TEST 2: user is no member of an event
        $this->assertFalse(Check::isMemberOfEvent(11));
        $this->assertFalse(Check::isMemberOfEvent(27));
    }

    public function testIsMyPrivateEvent() {

        $this->loginWithDBUser(5);

        // TEST 1: it is the private event of the user
        $this->assertTrue(Check::isMyPrivateEvent(4));

        // TEST 2: it is not the private event of the user
        $this->assertFalse(Check::isMyPrivateEvent(7));
        $this->assertFalse(Check::isMyPrivateEvent(35));
    }

    public function testIsPublicGroup() {

        // TEST 1: the group is public
        $this->assertTrue(Check::isPublicGroup(8));

        // TEST 2: the group is private
        $this->assertFalse(Check::isPublicGroup(6));
        $this->assertFalse(Check::isPublicGroup(11));
    }

    public function testIsPublicEvent() {

        // TEST 1: public group is assigned to an event --> public event
        $this->assertTrue(Check::isPublicEvent(2));

        // TEST 2: private group is assigned to an event --> private event
        $this->assertFalse(Check::isPublicEvent(1));
        $this->assertFalse(Check::isPublicEvent(13));
    }
}
