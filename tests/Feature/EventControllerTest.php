<?php

namespace Tests\Feature;

use App\Event;
use SeedConstants;
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
        for($i = 1; $i <= SeedConstants::NUM_EVENTS; $i++) {

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
        $this->assertTrue(count(self::USERS_EVENTS) == SeedConstants::NUM_USERS);

        // iterate over all users
        for($i = 0; $i < SeedConstants::NUM_USERS; $i++) {

            // pseudo login with the current user id
            $this->loginWithDBUser($i + 1);

            // iterate over all events
            for($k = 0; $k < SeedConstants::NUM_EVENTS; $k++) {

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

        // user not logged in -> permission must be denied, with and without group id
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/login');

        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 1]);
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);

        // create private event -> should succeed
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/home');

        // create event in group without any permissions -> should fail
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #2', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 6]);
        $response->assertForbidden();

        // create event in subscribed group -> should fail
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #2', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 7]);
        $response->assertForbidden();

        // create event in group (membership) -> should succeed
        $this->loginWithDBUser(2);
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #3', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30', 'selectGroup' => 2]);
        $response->assertRedirect('/home');

        // create event as all-day event -> should succeed
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #4', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'all-day-event' => 'true']);
        $response->assertRedirect('/home');

        // leave out mandatory information -> should fail
        $response = $this->from('/event/create')->post('/event',
            ['name' => 'My custom event #5', 'description' => 'ABC', 'location' => 'XYZ', 'selectGroup' => 2]);
        $response->assertRedirect('/event/create'); // redirect to input form
    }

    // test edit page for an event
    public function testEdit() {
        
        // user not logged in - expect redirect to start page
        $response = $this->get('/event/1/edit');
        $response->assertRedirect('/login');

        // user logged in - membership -> should succeed
        $this->loginWithDBUser(2);
        $response = $this->get('/event/1/edit');
        $response->assertOk();

        $response = $this->get('/event/5/edit');
        $response->assertOk();

        // user logged in - subscription -> should fail
        $this->loginWithDBUser(5);
        $response = $this->get('/event/6/edit');
        $response->assertForbidden();

        // user logged in - private event -> should succeed
        $this->loginWithDBUser(5);
        $response = $this->get('/event/4/edit');
        $response->assertOk();
        
    }

    // test updating an event
    public function testUpdate() {

        // user not logged in -> permission must be denied
        $response = $this->from('/event/1/edit')->put('/event/1',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/login');

        $response = $this->from('/event/2/edit')->put('/event/2',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(5);

        // create private event -> should succeed
        $response = $this->from('/event/4/edit')->put('/event/4',
            ['name' => 'My custom event #1', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/event/4');

        // update event in group without any permissions -> should fail
        $response = $this->from('/event/1/edit')->put('/event/1',
            ['name' => 'My custom event #2', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertForbidden();

        // update event in subscribed group -> should fail
        $response = $this->from('/event/6/edit')->put('/event/6',
            ['name' => 'My custom event #2', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertForbidden();

        // update event in group (membership) -> should succeed
        $this->loginWithDBUser(2);
        $response = $this->from('/event/1/edit')->put('/event/1',
            ['name' => 'My custom event #3', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'start-time' => '16:00', 'end-date' => '2019-01-01', 'end-time' => '18:30']);
        $response->assertRedirect('/event/1');

        // update all-day event -> should succeed
        $response = $this->from('/event/5/edit')->put('/event/5',
            ['name' => 'My custom event #4', 'description' => 'ABC', 'location' => 'XYZ', 'start-date' => '2019-01-01', 'all-day-event' => 'true']);
        $response->assertRedirect('/event/5');

        // leave out mandatory information -> should fail
        $response = $this->from('/event/5/edit')->put('/event/5',
            ['name' => 'My custom event #5', 'description' => 'ABC', 'location' => 'XYZ']);
        $response->assertRedirect('/event/5/edit');
    }

    // test updating an event
    public function testDestroy() {

        // user not logged in -> should fail
        $response = $this->delete('/event/1');
        $response->assertRedirect('/login');
        $event = Event::find(1);
        $this->assertNotNull($event);

        $response = $this->delete('/event/2');
        $response->assertRedirect('/login');
        $event = Event::find(2);
        $this->assertNotNull($event);

        // user logged in
        $this->loginWithDBUser(5);

        // delete private event -> should succeed
        $response = $this->delete('/event/4');
        $event = Event::find(4);
        $this->assertNull($event);

        // delete event in group without any permissions -> should fail
        $response = $this->delete('/event/1');
        $response->assertForbidden();
        $event = Event::find(1);
        $this->assertNotNull($event);


        // delete event in subscribed group -> should fail
        $response = $this->delete('/event/6');
        $response->assertForbidden();
        $event = Event::find(6);
        $this->assertNotNull($event);

        // delete event in group (membership) -> should succeed
        $this->loginWithDBUser(2);
        $response = $this->delete('/event/10');
        $event = Event::find(10);
        $this->assertNull($event);



    }
}
