<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    /**
     * A basic test for the home page.
     *
     * @return void
     */
    public function testIndex()
    {
        // user not logged in - expect redirect to start page
        $response = $this->get('/home');
        $response->assertRedirect('/login');

        // login user with id 2
        $this->loginWithDBUser(2);

        // user logged in - enters home page
        $response = $this->get('/home');
        $response->assertStatus(200);
    }
}
