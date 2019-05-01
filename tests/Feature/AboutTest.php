<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboutTest extends TestCase
{
    /**
     * A basic test for the about page
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }
}
