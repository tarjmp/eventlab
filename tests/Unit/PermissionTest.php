<?php

namespace Tests\Unit;

use App\Tools\Permission;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    //Methods Has test

    public function testHasShowGroup()
    {
        //User not logged in and private Group
        $this->assertFalse(Permission::has(Permission::showGroup, 2));

        //User not logged in and public Group
        $this->assertTrue(Permission::has(Permission::showGroup, 1));

        //User logged in and private Group he is not member
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::showGroup, 6));

        //User logged in and private Group he is member
        $this->loginWithDBUser(8);
        $this->assertTrue(Permission::has(Permission::showGroup, 6));

        //User logged in and public Group
        $this->loginWithDBUser(8);
        $this->assertTrue(Permission::has(Permission::showGroup, 6));
    }

    public function testHasShowGroupExtended()
    {
        //User not logged in and private Group
        $this->assertFalse(Permission::has(Permission::showGroupExtended, 2));

        //User not logged in and public Group
        $this->assertFalse(Permission::has(Permission::showGroupExtended, 1));

        //User logged in and private Group he is not member
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::showGroupExtended, 6));

        //User logged in and private Group he is member
        $this->loginWithDBUser(8);
        $this->assertTrue(Permission::has(Permission::showGroupExtended, 6));

        //User logged in and public Group
        $this->assertTrue(Permission::has(Permission::showGroupExtended, 6));
    }

    public function testHasCreateGroup()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::createGroup));

        //User logged in
        $this->loginWithDBUser(6);
        $this->assertTrue(Permission::has(Permission::createGroup));

        //Other user logged in
        $this->loginWithDBUser(8);
        $this->assertTrue(Permission::has(Permission::createGroup));
    }

    public function testHasEditGroup()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::editGroup, 6));

        //User logged in but not member of group
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::editGroup, 6));

        //User logged in and member of group
        $this->loginWithDBUser(8);
        $this->assertTrue(Permission::has(Permission::editGroup, 6));
    }

    public function testHasSubscribeToGroup()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::subscribeToGroup, 6));

        //User logged in but private group
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::subscribeToGroup, 6));

        //User logged in and member of group
        $this->loginWithDBUser(6);
        $this->assertTrue(Permission::has(Permission::subscribeToGroup, 1));
    }

    public function testHasLeaveGroup()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::leaveGroup, 6));

        //User logged in but not in group
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::leaveGroup, 6));

        //User logged in and member of group
        $this->loginWithDBUser(8);
        $this->assertTrue(Permission::has(Permission::leaveGroup, 6));
    }

    public function testHasShowEvent()
    {
        //User not logged in and private event
        $this->assertFalse(Permission::has(Permission::showEvent, 4));

        //User not logged in and public event
        $this->assertTrue(Permission::has(Permission::showEvent, 2));

        //User logged in and not member of Event, private Event
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::showEvent, 4));

        //User logged in and member of Event, private Event
        $this->loginWithDBUser(5);
        $this->assertTrue(Permission::has(Permission::showEvent, 4));

        //User logged in and not member of Event, public Event
        $this->loginWithDBUser(6);
        $this->assertTrue(Permission::has(Permission::showEvent, 2));

        //User logged in and member of Event, pubic Event
        $this->loginWithDBUser(4);
        $this->assertTrue(Permission::has(Permission::showEvent, 2));
    }

    public function testHasShowEventExtended()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::showEventExtended, 4));

        //User logged in and not member of Event
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::showEventExtended, 4));

        //User logged in and member of Event
        $this->loginWithDBUser(5);
        $this->assertTrue(Permission::has(Permission::showEventExtended, 4));
    }

    public function testHasCreateEventForGroup()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::createEventForGroup, 2));

        //User logged in and not member of Event
        $this->loginWithDBUser(6);
        $this->assertFalse(Permission::has(Permission::createEventForGroup, 2));

        //User logged in and member of Event
        $this->loginWithDBUser(2);
        $this->assertTrue(Permission::has(Permission::createEventForGroup, 2));
    }

    public function testHasEditEvent()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::editEvent, 2));

        //User logged in and not member of Event
        $this->loginWithDBUser(8);
        $this->assertFalse(Permission::has(Permission::editEvent, 2));

        //User logged in and member of Event
        $this->loginWithDBUser(4);
        $this->assertTrue(Permission::has(Permission::editEvent, 2));
    }

    public function testHasDeleteEvent()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::deleteEvent, 2));

        //User logged in and not member of Event
        $this->loginWithDBUser(8);
        $this->assertFalse(Permission::has(Permission::deleteEvent, 2));

        //User logged in and member of Event
        $this->loginWithDBUser(4);
        $this->assertTrue(Permission::has(Permission::deleteEvent, 2));
    }

    public function testHasRespondToEvent()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::respondToEvent, 2));

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(8);
        $this->assertFalse(Permission::has(Permission::respondToEvent, 4));

        //User logged in and member of Event and private event
        $this->loginWithDBUser(5);
        $this->assertTrue(Permission::has(Permission::respondToEvent, 4));

        //User logged in and public event
        $this->loginWithDBUser(9);
        $this->assertTrue(Permission::has(Permission::respondToEvent, 2));
    }

    public function testHasEditProfile()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::editProfile));

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(Permission::has(Permission::editProfile));
    }

    public function testHasShowHomeCalendar()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::showHomeCalendar));

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(Permission::has(Permission::showHomeCalendar));
    }

    public function testHasShowGroups()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::showGroups));

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(Permission::has(Permission::showGroups));
    }

    public function testHasCreateEvent()
    {
        //User not logged in
        $this->assertFalse(Permission::has(Permission::createEvent));

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(Permission::has(Permission::createEvent));
    }


    //Methods Check test

    public function testCheckEditGroupNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        Permission::check(Permission::editGroup);
    }

    public function testCheckLeaveGroupNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        Permission::check(Permission::leaveGroup);
    }

    public function testCheckEditEventNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        Permission::check(Permission::editEvent);
    }

    public function testCheckDeleteEventNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        Permission::check(Permission::deleteEvent);
    }
}
