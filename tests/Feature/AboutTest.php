<?php

namespace Tests\Feature;

use Tests\TestCase;

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
        $response->assertok();
    }
}
