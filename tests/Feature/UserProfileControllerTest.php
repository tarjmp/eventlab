<?php

namespace Tests\Feature;

use Tests\TestCase;


class UserProfileControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRead()
    {
        //User not logged in
        $response = $this->get('/profile');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        //User logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/profile');
        $response->assertOk();
    }
}
