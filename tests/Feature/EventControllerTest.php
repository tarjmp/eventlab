<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventControllerTest extends TestCase
{
    public function testCreate()
    {
        // user not logged in
        $response = $this->get('/event/create');
        $response->assertStatus(302);

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/event/create');
        $response->assertStatus(200);
    }

    public function testShow() {
        // TODO check forbidden events, etc

        //$response = $this->get('/event/1');
        //$response->assert
        $this->assertTrue(true);
    }


}
