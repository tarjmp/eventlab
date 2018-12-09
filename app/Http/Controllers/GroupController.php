<?php

namespace App\Http\Controllers;

use App\Group;
use App\Tools\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('group-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        Permission::check(Permission::createGroup);

        $data = $request->all();
        // never trust any user input

        Validator::make($data, [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
        ])->validate();

        $members      = explode(',', $data['members']);
        $participants = array();
        foreach ($members as $m) {
            $user = User::findOrFail($m);
            if ($user->id != Auth::user()->id) {
                $participants[] = $user;
            }
        }

        if (count($participants) == 0) {
            return redirect('home');
        }

        $participants[] = Auth::user();

        // Create new event from passed data
        $group              = new Group();
        $group->name        = $data['name'];
        $group->description = $data['description'];
        if (isset($data['privacy']) && $data['privacy'] == 'public') {
            $group->public = true;
        }

        $group->save();

        foreach ($participants as $p) {
            $group->members()->attach($p, ['status' => Group::TYPE_MEMBERSHIP]);
        }

        // redirect to home calendar
        return redirect('groups')->with('newGroup', $data['name']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groups()
    {


        $groups = Auth::user()->groups()->get();

        // convert the database timestamps to data and time in the user's timezone
        /*$start = new CustomDateTime($group->start_time);
        $end   = new CustomDateTime($group->end_time);*/

        return view('group-interface')->with(['groups' => $groups]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function participants()
    {


        $participants = User::all()->where('id', '!=', Auth::user()->id);

        // convert the database timestamps to data and time in the user's timezone
        /*$start = new CustomDateTime($group->start_time);
        $end   = new CustomDateTime($group->end_time);*/

        return view('group-select-participants')->with(['participants' => $participants]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function addParticipants(Request $request)
    {
        $data = $request->all();
        return redirect(route('group.create'))->with('members', implode(',', $data['members']));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {

        Permission::check(Permission::showGroup, $id);

        // retrieve the corresponding event from database
        $group = Group::findOrFail($id);

        // convert the database timestamps to data and time in the user's timezone
        /*$start = new CustomDateTime($group->start_time);
        $end   = new CustomDateTime($group->end_time);*/

        return view('group-show')->with(['group' => $group]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        Permission::check(Permission::editGroup, $id);

        // retrieve the corresponding event from database
        $group = Group::findOrFail($id);

        return view('group-update')->with(['id' => $id, 'group' => $group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        Permission::check(Permission::editGroup, $id);

        // retrieve the corresponding event from database
        $data  = $request->all();
        $group = Group::findOrFail($id);

        Validator::make($data, [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
        ])->validate();

        $group->name        = $data['name'];
        $group->description = $data['description'];
        /*if (isset($data['privacy']) && $data['privacy'] == 'public') {
            $group->public = true;
        }*/

        $group->save();

        // redirect to show group
        return redirect(route('group.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function leave(Request $request)
    {
        $data = $request->all();
        Permission::check(Permission::leaveGroup, $data['id']);

        // retrieve event from database and delete it
        $group = Group::findOrFail($data['id']);
        $group->members()->detach(Auth::user());

        if(count($group->members)==0) {
            $group->delete();
        }

        // redirect to home screen and show alert message
        return redirect('home')->with('GroupLeft', $group->name);
    }
}
