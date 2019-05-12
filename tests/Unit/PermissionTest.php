<?php

namespace Tests\Unit;

use App\Tools\Permission;
use App\Tools\PermissionFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    //Methods Has test

    public function testHasShowGroup()
    {
        //User not logged in and private Group
        $this->assertFalse(PermissionFactory::createShowGroup()->has(2));

        //User not logged in and public Group
        $this->assertTrue(PermissionFactory::createShowGroup()->has(1));

        //User logged in and private Group he is not member
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createShowGroup()->has(6));

        //User logged in and private Group he is member
        $this->loginWithDBUser(8);
        $this->assertTrue(PermissionFactory::createShowGroup()->has(6));

        //User logged in and public Group
        $this->loginWithDBUser(8);
        $this->assertTrue(PermissionFactory::createShowGroup()->has(1));
    }

    public function testHasShowGroupExtended()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createShowGroupExtended()->has(2));

        //User logged in and Group he is not member
        $this->loginWithDBUser(1);
        $this->assertFalse(PermissionFactory::createShowGroupExtended()->has(2));

        //User logged in and Group he is member
        $this->loginWithDBUser(2);
        $this->assertTrue(PermissionFactory::createShowGroupExtended()->has(2));
    }

    public function testHasCreateGroup()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createCreateGroup()->has());

        //User logged in
        $this->loginWithDBUser(6);
        $this->assertTrue(PermissionFactory::createCreateGroup()->has());

        //Other user logged in
        $this->loginWithDBUser(8);
        $this->assertTrue(PermissionFactory::createCreateGroup()->has());
    }

    public function testHasEditGroup()
    {
        //User not logged in and private group
        $this->assertFalse(PermissionFactory::createEditGroup()->has(6));

        //User not logged in and public group
        $this->assertFalse(PermissionFactory::createEditGroup()->has(5));

        //User logged in but not member of private group
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createEditGroup()->has(6));

        //User logged in but not member of public group
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createEditGroup()->has(5));

        //User logged in and member of group
        $this->loginWithDBUser(8);
        $this->assertTrue(PermissionFactory::createEditGroup()->has(6));
    }

    public function testHasSubscribeToGroup()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createSubscribeToGroup()->has(6));

        //User not logged in and public group
        $this->assertFalse(PermissionFactory::createSubscribeToGroup()->has(5));

        //User logged in and private group he is not member
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createSubscribeToGroup()->has(6));

        //User logged in and public group he is member
        $this->loginWithDBUser(4);
        $this->assertFalse(PermissionFactory::createSubscribeToGroup()->has(3));

        //User logged in and public group he is not member
        $this->loginWithDBUser(1);
        $this->assertTrue(PermissionFactory::createSubscribeToGroup()->has(1));
    }

    public function testHasLeaveGroup()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createLeaveGroup()->has(6));

        //User logged in but not in group
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createLeaveGroup()->has(6));

        //User logged in and member of group
        $this->loginWithDBUser(8);
        $this->assertTrue(PermissionFactory::createLeaveGroup()->has(6));
    }

    public function testHasShowEvent()
    {
        //User not logged in and private event
        $this->assertFalse(PermissionFactory::createShowEvent()->has(4));

        //User not logged in and public event
        $this->assertTrue(PermissionFactory::createShowEvent()->has(2));

        //User logged in and not member of Event, private Event
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createShowEvent()->has(4));

        //User logged in and member of Event, private Event
        $this->loginWithDBUser(5);
        $this->assertTrue(PermissionFactory::createShowEvent()->has(4));

        //User logged in and not member of Event, public Event
        $this->loginWithDBUser(6);
        $this->assertTrue(PermissionFactory::createShowEvent()->has(2));

        //User logged in and member of Event, public Event
        $this->loginWithDBUser(4);
        $this->assertTrue(PermissionFactory::createShowEvent()->has(2));
    }

    public function testHasShowEventExtended()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createShowEventExtended()->has(4));

        //User not logged in and public event
        $this->assertFalse(PermissionFactory::createShowEventExtended()->has(2));

        //User logged in and not member of Event
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createShowEventExtended()->has(4));

        //public event for logged-in user not member
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createShowEventExtended()->has(2));

        //User logged in and member of Event
        $this->loginWithDBUser(5);
        $this->assertTrue(PermissionFactory::createShowEventExtended()->has(4));
    }

    public function testHasCreateEventForGroup()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createCreateEventForGroup()->has(2));

        //User logged in and not member of Event
        $this->loginWithDBUser(6);
        $this->assertFalse(PermissionFactory::createCreateEventForGroup()->has(2));

        //User logged in and member of Event
        $this->loginWithDBUser(2);
        $this->assertTrue(PermissionFactory::createCreateEventForGroup()->has(2));
    }

    public function testHasEditEvent()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createEditEvent()->has(2));

        //User logged in and not member of Event
        $this->loginWithDBUser(8);
        $this->assertFalse(PermissionFactory::createEditEvent()->has(2));

        //User logged in and member of Event
        $this->loginWithDBUser(4);
        $this->assertTrue(PermissionFactory::createEditEvent()->has(2));
    }

    public function testHasDeleteEvent()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createDeleteEvent()->has(2));

        //User logged in and not member of Event
        $this->loginWithDBUser(8);
        $this->assertFalse(PermissionFactory::createDeleteEvent()->has(2));

        //User logged in and member of Event
        $this->loginWithDBUser(4);
        $this->assertTrue(PermissionFactory::createDeleteEvent()->has(2));
    }

    public function testHasRespondToEvent()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createRespondToEvent()->has(2));

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(8);
        $this->assertFalse(PermissionFactory::createRespondToEvent()->has(4));

        //User logged in and member of Event and private event
        $this->loginWithDBUser(5);
        $this->assertTrue(PermissionFactory::createRespondToEvent()->has(4));

        //User logged in and public event
        $this->loginWithDBUser(9);
        $this->assertTrue(PermissionFactory::createRespondToEvent()->has(2));
    }

    public function testHasEditProfile()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createEditProfile()->has());

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(PermissionFactory::createEditProfile()->has());
    }

    public function testHasShowHomeCalendar()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createShowHomeCalendar()->has());

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(PermissionFactory::createShowHomeCalendar()->has());
    }

    public function testHasShowGroups()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createShowGroups()->has());

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(PermissionFactory::createShowGroups()->has());
    }

    public function testHasCreateEvent()
    {
        //User not logged in
        $this->assertFalse(PermissionFactory::createCreateEvent()->has());

        //User logged in and not member of Event and private event
        $this->loginWithDBUser(1);
        $this->assertTrue(PermissionFactory::createCreateEvent()->has());
    }


    //Methods Check test

    public function testCheckEditGroupNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        PermissionFactory::createEditGroup()->check(1);
    }

    public function testCheckLeaveGroupNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        PermissionFactory::createLeaveGroup()->check(1);
    }

    public function testCheckEditEventNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        PermissionFactory::createEditEvent()->check(1);
    }

    public function testCheckDeleteEventNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        PermissionFactory::createDeleteEvent()->check(1);
    }
}
