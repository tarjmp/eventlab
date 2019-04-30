<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // This method fakes a user login for test cases
    public function loginWithFakeUser($user = null) {

        if($user == null) {
            $user = new User();
        }

        if (empty($user->id)) {
            $user->id = 1;
        }
        if (empty($user->name)) {
            $user->name = 'Test';
        }
        if (empty($user->timezone)) {
            $user->timezone = 'UTC';
        }

        $this->be($user);
    }

    // This method logs in as the user with the given id, only for test cases
    public function loginWithDBUser($id) {

        // find user in database
        $user = User::find($id);

        // assert that user with specified id existed
        $this->assertTrue(boolval($user));

        // login with this user
        $this->loginWithFakeUser($user);
    }
}
