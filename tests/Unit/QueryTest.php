<?php

namespace Tests\Unit;

use App\Tools\Query;
use App\User;
use SeedConstants;
use Tests\TestCase;

class QueryTest extends TestCase
{

    // this array contains an array of events for each user (including private events, subscriptions, memberships)
    // CAUTION: index = user; for each user: array of events
    private const USERS_EVENTS = [[], [1, 5, 10], [7], [1, 2, 5, 6, 10], [2, 4, 6], [], [9], [3], [], [8]];

    /**
     * Test for Query class (app/Tools/Query)
     *
     * @return void
     */
    public function testGetUserEvents()
    {
        // make sure that there is enough validation data to test all events
        $this->assertTrue(count(self::USERS_EVENTS) == SeedConstants::NUM_USERS);

        // iterate over all users
        for($i = 0; $i < SeedConstants::NUM_USERS; $i++) {

            // pseudo login with the current user id
            $this->loginWithDBUser($i + 1);

            // call the function to be tested and only save the column 'id' of the events
            $aTestResult = array_column(Query::getUserEvents(true)->get()->toArray(), 'id');

            // iterate over all events
            for($k = 0; $k < SeedConstants::NUM_EVENTS; $k++) {

                if(in_array($k + 1, self::USERS_EVENTS[$i], true)) {
                    $this->assertTrue(in_array($k + 1, $aTestResult, true));
                }
                else {
                    $this->assertNotTrue(in_array($k + 1, $aTestResult, true));
                }
            }
        }
    }
}
