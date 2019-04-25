<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginWithFakeUser() {
        $user = new User([
            'id' => 1,
            'name' => 'test',
            'timezone' => 'UTC',
        ]);

        $this->be($user);
    }
}
