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

    public function testDieInvalidRequest()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_INVALID_REQUEST);
    }

    public function testDieReasonNotFound()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_NOT_FOUND);
    }

    public function testDieReasonInternalServerError()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_INTERNAL_SERVER_ERROR);
    }
}
