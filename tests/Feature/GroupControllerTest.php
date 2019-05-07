<?php

namespace Tests\Feature;

use App\Http\Controllers\GroupController;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    /**
     * A test for class GroupController.
     *
     * @return void
     */
    // test creation page for new events
    public function testCreate()
    {

        // user not logged in - expect redirect to start page
        $response = $this->get('/group/create');
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/group/create');
        $response->assertOk();
    }

    public function testStore()
    {

        // user not logged in -> permission must be denied, whether temporary or public is true or false
        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #1', 'description' => 'ABC', 'temporary' => 'true', 'public' => 'false']);
        $response->assertRedirect('/login');

        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #1', 'description' => 'ABC', 'temporary' => 'false', 'public' => 'true']);
        $response->assertRedirect('/login');

        // user logged in --> test should succeed
        $this->loginWithDBUser(1);

        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #1', 'description' => 'ABC', 'temporary' => 'true', 'public' => 'false']);
        $response->assertRedirect('/group/create');

        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #1', 'description' => 'ABC', 'temporary' => 'false', 'public' => 'true']);
        $response->assertRedirect('/group/create');

    }

    public function testGroups()
    {

        // user not logged in - expect redirect to start page
        $response = $this->get('/groups');
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/groups');
        $response->assertOk();

    }
}

  /*  public function testParticipants() {

        $group = new GroupController();

        var_dump( $group->participants());


    }

   // public function testAddParticipants() {

   // }

  //  public function testShow() {

   // }

   // public function testEdit() {

    // }

   // public function testUpdate() {

   // }

  //  public function testLeave() {

   // }


}
