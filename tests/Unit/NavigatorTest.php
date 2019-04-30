<?php

namespace Tests\Unit;

use App\Tools\Navigator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class NavigatorTest extends TestCase
{
    //User not logged in and no reason passed
    public function testDieNotLoggedInDefault()
    {
        $this->expectException(HttpException::class);
        Navigator::die();
    }

    //User not logged in and no reason passed
    public function testDieNotAuthorizedDefault()
    {
        $this->loginWithFakeUser();
        $this->expectException(HttpException::class);
        Navigator::die();
    }

    //User not authorized and not logged in
    public function testDieNotLoggedInReasonUnauthorized()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_UNAUTHORIZED);
    }

    //User not authorized and logged in
    public function testDieNotAuthorizedReasonUnauthorized()
    {
        $this->loginWithFakeUser();
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_UNAUTHORIZED);
    }

    //User send an invalid Request
    public function testDieInvalidRequest()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_INVALID_REQUEST);
    }

    //User send an action he is not authorized
    public function testDieReasonNotFound()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_NOT_FOUND);
    }

    //User created an internal server error
    public function testDieReasonInternalServerError()
    {
        $this->expectException(HttpException::class);
        Navigator::die(Navigator::REASON_INTERNAL_SERVER_ERROR);
    }
}
