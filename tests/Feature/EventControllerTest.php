<?php

namespace Tests\Feature;

use Tests\TestCase;

class EventControllerTest extends TestCase
{

    // this array contains an array of events for each user (including private events, subscriptions, memberships)
    // CAUTION: index = user; for each user: array of events
    private const USERS_EVENTS  = [[], [1, 5, 10], [7], [1, 2, 5, 6, 10], [2, 4, 6], [], [9], [3], [], [8]];
    private const PUBLIC_EVENTS = [2, 6];

    // test creation page for new events
    public function testCreate()
    {
        // user not logged in - expect redirect to start page
        $response = $this->get('/event/create');
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/event/create');
        $response->assertOk();
    }

    // test display functionality for events
    public function testShow() {

        // test access to all events without being logged in
        for($i = 1; $i <= \SeedConstants::NUM_EVENTS; $i++) {

            $response = $this->get("/event/$i");
            if(in_array($i, self::PUBLIC_EVENTS, true)) {
                // access to public events must be granted
                $response->assertOk();
            }
            else {
                // access to non-public events must be denied (redirect to login page)
                $response->assertRedirect('/login');
            }
        };

        // check accessible events for each user
        // make sure that there is enough validation data to test all events
        $this->assertTrue(count(self::USERS_EVENTS) == \SeedConstants::NUM_USERS);

        // iterate over all users
        for($i = 0; $i < \SeedConstants::NUM_USERS; $i++) {

            // pseudo login with the current user id
            $this->loginWithDBUser($i + 1);

            // iterate over all events
            for($k = 0; $k < \SeedConstants::NUM_EVENTS; $k++) {

                $response = $this->get('/event/' . ($k + 1));

                // access must be granted to public events and to events the user belongs to (membership, private)
                if(in_array($k + 1, self::PUBLIC_EVENTS, true) || in_array($k + 1, self::USERS_EVENTS[$i], true)) {
                    $response->assertOk();
                }
                else {
                    $response->assertForbidden();
                }
            }
        }
    }

    // test creation of a new event (store in database)
    public function testStore() {

        // user not logged in -> permission must be denied
        $response = $this->json('POST', '/event',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);

        // create private event -> should succeed
        $response = $this->followingRedirects()->json('POST', '/event',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertOk();
        $response->assertSeeText('My custom event #1');

        // create event in group without any permissions -> should fail
        $response = $this->followingRedirects()->json('POST', '/event',
            ['name' => 'My custom event #2', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 6]);
        $response->assertForbidden();

        // create event in subscribed group -> should fail
        $response = $this->followingRedirects()->json('POST', '/event',
            ['name' => 'My custom event #2', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 7]);
        $response->assertForbidden();

        // create event in group (membership) -> should succeed
        $this->loginWithDBUser(2);
        $response = $this->followingRedirects()->json('POST', '/event',
            ['name' => 'My custom event #3', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 2]);
        $response->assertOk();
        $response->assertSeeText('My custom event #3');

        // leave out mandatory information -> should fail
        $response = $this->followingRedirects()->json('POST', '/event',
            ['name' => 'My custom event #4', 'description' => 'ABC', 'location' => 'XYZ', 'selectGroup' => 2]);
        $response->assertStatus(500);
    }
}
