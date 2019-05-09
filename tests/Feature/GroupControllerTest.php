<?php

namespace Tests\Feature;

use App\Group;
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

        // user is not logged in
        // -------------------------------------------------------------------------------------------------------
        // permission must be denied, whether temporary or public is true or false
        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #1', 'description' => 'ABC', 'temporary' => 'true', 'public' => 'false']);
        $response->assertRedirect('/login');

        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #2', 'description' => 'ABC', 'temporary' => 'false', 'public' => 'true']);
        $response->assertRedirect('/login');

        // user logged in
        // -------------------------------------------------------------------------------------------------------
        $this->loginWithDBUser(1);

        // group is not created -> missing members
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #3', 'description' => 'ABC', 'temporary' => 'true', 'public' => 'false']);
        $response->assertOk();

        // group not created -> missing group name
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['description' => 'ABC', 'temporary' => 'true', 'public' => 'false', 'members' => '4,5,10']);
        $response->assertOk();

        // group not created -> only logged in user as member
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #4', 'description' => 'ABC', 'temporary' => 'true', 'public' => 'false', 'members' => '1,1,1']);
        $response->assertForbidden();

        // group not created -> missing content
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            []);
        $response->assertOk();

        // group created -> member that has the same id as logged in user is not added to group
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #5', 'description' => 'ABC', 'temporary' => 'false', 'public' => 'true', 'members' => '1,2']);
        $response->assertOk();
        $this->assertGroupExists(11);

        // group created -> only one member with id 4 is added to the group
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #6', 'description' => 'ABC', 'temporary' => 'true', 'public' => 'false', 'members' => '4,4,4']);
        $response->assertOk();
        $this->assertGroupExists(12);

        // group created -> all members are added to group
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #7', 'description' => 'ABC', 'temporary' => 'false', 'public' => 'true', 'members' => '4,5,10']);
        $response->assertOk();
        $this->assertGroupExists(13);

        // group created -> privacy is public
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #8', 'description' => 'ABC', 'temporary' => 'false', 'privacy' => 'public', 'members' => '4,5,10']);
        $response->assertOk();
        $this->assertGroupExists(14);

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


    public function testParticipants()
    {

        // user not logged in - expect redirect to start page
        $response = $this->get('/group/new');
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/group/new');
        $response->assertOk();


    }

   /* public function testAddParticipants() {

        //User not logged in
        $response = $this->from('group/new')->post('/group/create', ['group']);
        $response->assertRedirect('/login');

    }*/

  //  public function testShow() {

   // }

   // public function testEdit() {

    // }

   // public function testUpdate() {

   // }

  //  public function testLeave() {

   // }

    private function assertGroupExists($id) {
        $group = Group::find($id);
        $this->assertNotNull($group);

    }


}
