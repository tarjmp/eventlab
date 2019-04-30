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
