<?php

namespace Tests\Feature;

use App\Http\Controllers\UserProfileController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    }
}
