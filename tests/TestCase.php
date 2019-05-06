<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // This method fakes a user login for test cases
    protected function loginWithFakeUser($user = null) {

        if($user == null) {
            $user = new User();
        }

        if (empty($user->id)) {
            $user->id = 1;
        }
        if (empty($user->first_name)) {
            $user->first_name = 'John';
        }
        if (empty($user->last_name)) {
            $user->last_name = 'Doe';
        }
        if (empty($user->timezone)) {
            $user->timezone = 'UTC';
        }

        $this->be($user);
    }

    // This method logs in as the user with the given id, only for test cases
    protected function loginWithDBUser($id) {

        // find user in database
        $user = User::find($id);

        // assert that user with specified id existed
        $this->assertTrue(boolval($user));

        // login with this user
        $this->loginWithFakeUser($user);
    }

    // This method can be used for controller tests to set the previous URL (the URL the user is coming from)
    public function from($url) {
        $this->app['session']->setPreviousUrl($url);
        return $this;
    }

    protected function setUp() {
        parent::setUp();

        // clear database tables and execute seeding
        $seeder = new \DatabaseSeeder();
        $seeder->run();
    }

    protected function tearDown() {
        parent::tearDown();
    }
}
