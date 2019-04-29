<?php

namespace Tests\Unit;

use App\Tools\Query;
use App\User;
use Tests\TestCase;

class QueryTest extends TestCase
{

    // this array contains an array of events for each user (including private events, subscriptions, memberships)
    private const USERS_EVENTS = [[]];

    /**
     * Test for Query class (app/Tools/Query)
     *
     * @return void
     */
    public function testGetUserEvents()
    {
        $user = new User();

        // iterate over all users
        for($i = 0; $i < count(self::USERS_EVENTS); $i++) {

            // pseudo login with the current user id
            $user->id = $i + 1;
            $this->loginWithFakeUser($user);

            // call the function to be tested and only save the column 'id' of the events
            $aTestResult = array_column(Query::getUserEvents(), 'id');

            // iterate over all events
            for($k = 0; $k < 10; $k++) {

                if(in_array($k, self::USERS_EVENTS[$i], true)) {
                    $this->assertTrue(in_array($k, $aTestResult, true));
                }
                else {
                    $this->assertNotTrue(in_array($k, $aTestResult, true));
                }
            }
        }
    }
}
