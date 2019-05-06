<?php

namespace Tests\Feature;

use Tests\TestCase;


class UserProfileControllerTest extends TestCase
{
    /**
     * Feature test for the UserProfile controller
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
        //User not logged in
        $response = $this->from('/profile')->post('/profile', $this->generateFullData());
        $response->assertRedirect('/login');

        //User logged in and full data
        $this->loginWithDBUser(1);
        $response = $this->followingRedirects()->from('/profile')->post('/profile', $this->generateFullData());
        $response->assertOk();
        $response->assertViewHas('updated', true);
        $response->assertSeeText('Your profile was successfully updated.');

        //User logged in and minimal data
        $this->loginWithDBUser(1);
        $response = $this->followingRedirects()->from('/profile')->post('/profile', $this->generateMinData());
        $response->assertOk();
        $response->assertViewHas('updated', true);
        $response->assertSeeText('Your profile was successfully updated.');

        //User logged in and invalid data
        $this->loginWithDBUser(1);
        $response = $this->followingRedirects()->from('/profile')->post('/profile', $this->generateInvalidData());
        $response->assertOk();
        $response->assertSeeText('The first name field is required.');
        $response->assertSeeText('The last name field is required.');
        $response->assertSeeText('The email field is required.');

        //User logged in and email address already taken
        $this->loginWithDBUser(2);
        $response = $this->followingRedirects()->from('/profile')->post('/profile', $this->generateInvalidEmail());
        $response->assertOk();
        $response->assertSeeText('The email has already been taken.');
    }

    private function generateInvalidData(): array
    {
        //empty dataset is not allowed
        return array(
            "first_name" => null,
            "last_name" => null,
            "location" => null,
            "date_of_birth" => null,
            "email" => null);
    }

    private function generateMinData(): array
    {
        return array(
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "location" => null,
            "date_of_birth" => null,
            "email" => 'max.mustermann@e-mail.com');
    }

    private function generateFullData(): array
    {
        return array(
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "location" => "Musterstadt",
            "date_of_birth" => "1998-12-31",
            "email" => 'max.mustermann@e-mail.com');
    }

    private function generateInvalidEmail(): array
    {
        return array(
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "location" => "Musterstadt",
            "date_of_birth" => "1998-12-31",
            "email" => 'max.mustermann@e-mail.com');
    }
}
