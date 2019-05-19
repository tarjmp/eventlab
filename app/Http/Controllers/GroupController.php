<?php

namespace App\Http\Controllers;

use App\Group;
use App\Tools\Navigator;
use App\Tools\PermissionFactory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{

    // Show the form for creating a new resource
    public function create()
    {

        // check for appropriate permission
        PermissionFactory::createCreateGroup()->check();

        // simply show view
        return view('group-create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {

        // check for permission to create a group
        PermissionFactory::createCreateGroup()->check();

        $data = $request->all();

        // never trust any user input
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
            'members'     => 'required|string',
        ]);

        // read all members into an array
        $members = explode(',', $data['members']);

        // this variable will store all valid members
        $participants = array();
        foreach ($members as $m) {
            // find the corresponding user
            $user = User::findOrFail($m);

            // add the user to the list of members, but only if it is not the logged-in user
            // also prevent users from being inserted multiple times
            if ($user->id != Auth::user()->id && !in_array($user, $participants)) {
                $participants[] = $user;
            }
        }

        // If no participants have been added, simply fail
        if (count($participants) == 0) {
            Navigator::die(Navigator::REASON_INVALID_REQUEST);
        }

        // Also add the current user to the group :)
        $participants[] = Auth::user();

        // Create new group from passed data
        $group              = new Group();
        $group->name        = $data['name'];
        $group->description = $data['description'];

        // this privacy setting can only be applied once and not be changed afterwards!
        if (isset($data['privacy']) && $data['privacy'] == 'public') {
            $group->public = true;
        }

        // insert the group into our database
        $group->save();

        // add the members to our newly created group
        foreach ($participants as $p) {
            $group->members()->attach($p, ['status' => Group::TYPE_MEMBERSHIP]);
        }

        // redirect to group overview page
        return redirect('groups')->with('newGroup', $data['name']);
    }

    //Display the specified resource
    public function groups()
    {

        // require appropriate permission
        PermissionFactory::createShowGroups()->check();

        // retrieve all groups for the user and pass them to the view
        $groups = Auth::user()->groups()->orderBy('name')->get();
        return view('group-interface')->with(['groups' => $groups]);

    }

    // Show the form for adding participants to the group
    public function participants()
    {

        // check for appropriate permission
        PermissionFactory::createCreateGroup()->check();

        // list all users except for the current user himself ordered by first name of participants (asc)
        $participants = User::where('id', '!=', Auth::user()->id)->orderBy('first_name')->get();

        // show the corresponding view
        return view('group-select-participants')->with(['participants' => $participants, 'edit' => false]);

    }

    // Adding participants to group
    public function addParticipants(Request $request)
    {

        // check for appropriate permission
        PermissionFactory::createCreateGroup()->check();

        // validate the incoming request
        $request->validate(['members' => 'required|array|min:1']);

        // this function handles selected members of the group and passes them on to the group create function
        $data = $request->all();

        return redirect(route('group.create'))->with('members', implode(',', $data['members']));

    }

    // Show the form for editing the specified resource
    public function show($id)
    {

        // check for appropriate permission to show the group
        PermissionFactory::createShowGroup()->check($id);

        // retrieve the corresponding group from database and show view
        $group = Group::findOrFail($id);

        $numberSubscriptions = count($group->subscribers);


        return view('group-show')->with(['group' => $group, 'numberSubscriptions' => $numberSubscriptions]);

    }

    // Show the form for editing the specified resource
    public function edit($id)
    {

        // check for appropriate permission to edit the group
        PermissionFactory::createEditGroup()->check($id);

        // retrieve the corresponding group from database
        $group = Group::findOrFail($id);

        return view('group-update')->with(['id' => $id, 'group' => $group]);

    }

    // Show the form for adding new participants to an existing group
    public function newParticipants($id)
    {

        // check for appropriate permission to edit the group
        PermissionFactory::createEditGroup()->check($id);

        //retrieve all members of the corresponding group from database
        $members = Group::findOrFail($id)->members;

        // list all users except for the members of the group
        $participants = User::all()->diff($members);
        // sort array by first name of participants (asc)
        $participants = $participants->sortby('first_name');

        // show the corresponding view
        return view('group-select-participants')->with(['participants' => $participants, 'edit' => true, 'id' => $id]);
    }

    // Adding new participants to existing group
    public function addNewParticipants(Request $request, $id)
    {

        // check for appropriate permission
        PermissionFactory::createEditGroup()->check($id);

        $data = $request->all();

        // retrieve the corresponding group from database
        $group = Group::findOrFail($id);

        // validate the incoming request
        $request->validate(['members' => 'required|array|min:1']);

        // read all members into an array
        $members = $data['members'];

        // list all users except for the members of the group
        $participants = User::all()->whereIn('id', $members)->diff($group->members);

        // list all subscribers from the participants
        $subscribers = $participants->intersect($group->subscribers);

        // remove subscriptions
        $group->subscribers()->detach($subscribers);

        // add participants as new members
        $group->members()->attach($participants, ['status' => Group::TYPE_MEMBERSHIP]);

        // return to the group edit view
        return redirect(route('group.edit', $id));

    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {

        // check for appropriate permission to edit the group
        PermissionFactory::createEditGroup()->check($id);

        // retrieve the corresponding group from database
        $data  = $request->all();
        $group = Group::findOrFail($id);

        Validator::make($data, [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
        ])->validate();

        $group->name        = $data['name'];
        $group->description = $data['description'];

        // Please note that the privacy setting of the group cannot be edited here. That is supposed to be this way.
        // This prevents users from making a private group public by accident.

        $group->save();

        // redirect to show group
        return redirect(route('group.show', $id));
    }

    // Remove the specified resource from storage
    public function leave(Request $request)
    {

        // execute necessary permission check for leaving a group
        $data = $request->all();
        PermissionFactory::createLeaveGroup()->check($data['id']);

        // retrieve group from database and remove the user from the members list
        $group = Group::findOrFail($data['id']);
        $group->members()->detach(Auth::user());

        // Delete the group if there are no members anymore to save database space
        // #LaravelForEnvironment
        if (count($group->members) == 0) {
            $group->delete();
        }

        // redirect to home screen and show alert message
        return redirect('home')->with('GroupLeft', $group->name);
    }
}
