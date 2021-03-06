<?php

namespace Tests\Feature;

use App\Group;
use SeedConstants;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    /**
     * A test for class GroupController.
     *
     * @return void
     */

    // this array contains an array of groups (memberships) for each user
    // CAUTION: index = user; for each user: array of memberships
    private const USERS_MEMBERSHIPS = [[], [2], [9], [2, 3], [], [], [], [6], [], []];

    // test creation page for new groups
    public function testCreate()
    {

        // user not logged in --> expect redirect to login page
        $response = $this->get('/group/create');
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/group/create');
        $response->assertOk();
    }

    // test creation of a new group (store in database)
    public function testStore()
    {

        // user is not logged in
        // -------------------------------------------------------------------------------------------------------
        // permission must be denied, whether temporary or public is true or false
        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #1', 'description' => 'ABC', 'temporary' => 'true', 'privacy' => 'private']);
        $response->assertRedirect('/login');

        $response = $this->from('/group/create')->post('/group',
            ['name' => 'My custom group #2', 'description' => 'ABC', 'temporary' => 'false', 'privacy' => 'public']);
        $response->assertRedirect('/login');

        // user logged in
        // -------------------------------------------------------------------------------------------------------
        $this->loginWithDBUser(1);

        // group is not created --> missing members
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #3', 'description' => 'ABC', 'temporary' => 'true', 'privacy' => 'private']);
        $response->assertOk();

        // group not created --> missing group name
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['description' => 'ABC', 'temporary' => 'true', 'privacy' => 'private', 'members' => '4,5,10']);
        $response->assertOk();

        // group not created --> only logged in user as member
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #4', 'description' => 'ABC', 'temporary' => 'true', 'privacy' => 'private', 'members' => '1,1,1']);
        $response->assertForbidden();

        // group not created --> missing content
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            []);
        $response->assertOk();

        // group created --> member that has the same id as logged in user is not added to group
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #5', 'description' => 'ABC', 'temporary' => 'false', 'privacy' => 'public', 'members' => '1,2']);
        $response->assertOk();
        $this->assertGroupExists(11);

        // group created --> only one member with id 4 is added to the group
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #6', 'description' => 'ABC', 'temporary' => 'true', 'privacy' => 'private', 'members' => '4,4,4']);
        $response->assertOk();
        $this->assertGroupExists(12);

        // group created --> all members are added to group
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #7', 'description' => 'ABC', 'temporary' => 'false', 'privacy' => 'public', 'members' => '4,5,10']);
        $response->assertOk();
        $this->assertGroupExists(13);

        // group created --> privacy is public
        $response = $this->followingRedirects()->from('/group/create')->post('/group',
            ['name' => 'My custom group #8', 'description' => 'ABC', 'temporary' => 'false', 'privacy' => 'public', 'members' => '4,5,10']);
        $response->assertOk();
        $this->assertGroupExists(14);

    }

    // test overview page of a all groups to an user
    public function testGroups()
    {

        // user not logged in --> expect redirect to login page
        $response = $this->get('/groups');
        $response->assertOK();

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/groups');
        $response->assertOk();

    }

    // test page to select participants to new group
    public function testParticipants()
    {

        // user not logged in --> expect redirect to login page
        $response = $this->get('/group/new');
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(1);
        $response = $this->get('/group/new');
        $response->assertOk();


    }

    // test page to add participants to new group
    public function testAddParticipants()
    {

        // user is not logged in
        // -------------------------------------------------------------------------------------------------------
        // permission must be denied, whether members are set or not
        $response = $this->from('/group/new')->post('/group/new',
            ['members' => [1, 2, 3]]);
        $response->assertRedirect('/login');

        $response = $this->from('/group/new')->post('/group/new',
            []);
        $response->assertRedirect('/login');

        // user logged in
        // -------------------------------------------------------------------------------------------------------
        $this->loginWithDBUser(1);

        // members are added to group
        $response = $this->from('/group/new')->post('/group/new',
            ['members' => [1, 2, 3]]);
        $response->assertRedirect('/group/create');

        // no members selected --> only the logged in user
        $response = $this->from('/group/new')->post('/group/new',
            ['members' => [1, 1, 1]]);
        $response->assertRedirect('/group/create');

        // empty content --> fail
        $response = $this->from('/group/new')->post('/group/new',
            []);
        $response->assertRedirect('/group/new');

        // members are null --> fail
        $response = $this->from('/group/new')->post('/group/new',
            ['members' => null]);
        $response->assertRedirect('/group/new');

        // members are added to group
        $response = $this->from('/group/new')->post('/group/new',
            ['members' => [1, 2, 3, 4, 5]]);
        $response->assertRedirect('/group/create');

    }

    // test display functionality for groups
    public function testShow()
    {

        // test access to all groups without being logged in
        for ($i = 1; $i <= SeedConstants::NUM_GROUPS; $i++) {

            $response = $this->get("/group/$i");
            if (in_array($i, SeedConstants::GROUPS_PUBLIC, true)) {
                // access to public groups must be granted
                $response->assertOk();
            } else {
                // access to non-public groups must be denied (redirect to login page)
                $response->assertRedirect('/login');
            }
        };

        // check accessible groups for each user
        // make sure that there is enough validation data to test all groups
        $this->assertTrue(count(self::USERS_MEMBERSHIPS) == SeedConstants::NUM_USERS);


        // iterate over all users
        for ($i = 0; $i < SeedConstants::NUM_USERS; $i++) {

            // pseudo login with the current user id
            $this->loginWithDBUser($i + 1);

            // iterate over all groups
            for ($k = 0; $k < SeedConstants::NUM_GROUPS; $k++) {

                $response = $this->get('/group/' . ($k + 1));

                // access must be granted to public groups and to groups the user belongs to (membership)
                if (in_array($k + 1, SeedConstants::GROUPS_PUBLIC, true) || in_array($k + 1, self::USERS_MEMBERSHIPS[$i], true)) {
                    $response->assertOk();
                } else {
                    $response->assertForbidden();
                }
            }
        }

    }

    // test edit page for a group
    public function testEdit()
    {

        // user not logged in --> expect redirect to start page
        $response = $this->get('/group/1/edit');
        $response->assertRedirect('/login');

        // user logged in --> membership --> should succeed
        $this->loginWithDBUser(2);
        $response = $this->get('/group/2/edit');
        $response->assertOk();


        // user logged in --> subscription --> should fail
        $this->loginWithDBUser(5);
        $response = $this->get('/event/8/edit');
        $response->assertForbidden();

        $response = $this->get('/event/3/edit');
        $response->assertForbidden();

    }

    // test updating a group
    public function testUpdate()
    {

        // user not logged in --> permission must be denied
        $response = $this->from('/group/1/edit')->put('/group/1',
            ['name' => 'My custom group #1', 'description' => 'ABC']);
        $response->assertRedirect('/login');

        $response = $this->from('/group/2/edit')->put('/group/2',
            ['name' => 'My custom group #2', 'description' => 'ABC']);
        $response->assertRedirect('/login');

        // user logged in
        $this->loginWithDBUser(5);

        // logged in user only subscribed to group 8 --> fail
        $response = $this->followingRedirects()->from('/group/8/edit')->put('/group/8',
            ['name' => 'My custom group #3', 'description' => 'ABC']);
        $response->assertForbidden();

        // logged in user neither subscribed or has a membership to group 3 --> fail
        $response = $this->followingRedirects()->from('/group/3/edit')->put('/group/3',
            ['name' => 'My custom group #3', 'description' => 'ABC']);
        $response->assertForbidden();

        // user logged in
        $this->loginWithDBUser(4);

        // missing name of group --> fail
        $response = $this->followingRedirects()->from('/group/3/edit')->put('/group/3',
            ['description' => 'ABC']);
        $response->assertOk();

        // description is 'null' --> fail
        $response = $this->followingRedirects()->from('/group/3/edit')->put('/group/3',
            ['name' => 'My custom group #4', 'description' => null]);
        $response->assertOk();

        // group is updated
        $response = $this->followingRedirects()->from('/group/2/edit')->put('/group/2',
            ['name' => 'My custom group #5', 'description' => 'ABC']);
        $response->assertOk();

    }

    // test leaving a group
    public function testLeave()
    {

        // user not logged in --> should fail
        $response = $this->from('/group/1/edit')->post('/group/leave',
            ['id' => '1']);
        $response->assertRedirect('/login');
        $this->assertGroupExists(1);

        $response = $this->from('/group/2/edit')->post('/group/leave',
            ['id' => '2']);
        $response->assertRedirect('/login');
        $this->assertGroupExists(2);

        // user logged in
        $this->loginWithDBUser(5);

        // user is not a member of group --> fail
        $response = $this->followingRedirects()->from('/group/8/edit')->post('/group/leave',
            ['id' => '8']);
        $response->assertForbidden();
        $this->assertGroupExists(8);


        $this->loginWithDBUser(4);

        // user is member of group --> left group
        $response = $this->followingRedirects()->from('/group/2/edit')->post('/group/leave',
            ['id' => '2']);
        $response->assertOk();
        $this->assertGroupExists(2);


        $this->loginWithDBUser(2);

        // user is member of group --> left group --> because no members left the group is deleted
        $response = $this->followingRedirects()->from('/group/2/edit')->post('/group/leave',
            ['id' => '2']);
        $response->assertOk();
        $this->assertGroupDeleted(2);
    }


    // tests if group exists
    private function assertGroupExists($id)
    {
        $group = Group::find($id);
        $this->assertNotNull($group);

    }

    // test if group is deleted
    private function assertGroupDeleted($id)
    {
        $group = Group::find($id);
        $this->assertNull($group);
    }


}
