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
        $response->assertRedirect('/login');

        //User logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/profile');
        $response->assertOk();
    }

    public function testUpdate()
    {
        //Generate data to send
        $invalid_data = $this->generateInvalidData();
        $min_data = $this->generateMinData();
        $full_data = $this->generateFullData();


        //User not logged in
        $response = $this->from('/profile')->post('/profile', $min_data);
        $response->assertRedirect('/login');

    }

    private function generateInvalidData(): array
    {
        //last name missing
        $min_data = array(
            "first_name" => "Max",
            "email" => 'max.mustermann@e-mail.com');
        return $min_data;
    }

    private function generateMinData(): array
    {
        $min_data = array(
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "email" => 'max.mustermann@e-mail.com');
        return $min_data;
    }

    private function generateFullData(): array
    {
        $min_data = array(
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "location" => "Musterstadt",
            "date_of_birth" => "31/12/1998",
            "email" => 'max.mustermann@e-mail.com');
        return $min_data;
    }
}
