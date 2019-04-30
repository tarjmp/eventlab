<?php

namespace Tests\Unit;

use App\Tools\Permission;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function testHas()
    {
        $this->assertTrue(true);
    }

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
