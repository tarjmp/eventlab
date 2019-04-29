<?php

namespace Tests\Unit;

use App\Tools\Navigator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class NavigatorTest extends TestCase
{
    //Navigator::REASON_UNAUTHORIZED
    public function testDieNotLoggedIn()
    {
        $this->expectException(HttpException::class);
        Navigator::die();
    }

    public function testDieNotAuthorized()
    {
        $this->loginWithFakeUser();
        $this->expectException(HttpException::class);
        Navigator::die();
    }
}
