<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class ManageSubscriptionsController extends Controller
{

    //Show the read-only form of the What-to-bring list
    public function show()
    {
        return view('manage-subscriptions')->with(['items' => $this->getItems()]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        //User permission check is not required as the user is not submitted
        //Sending an invalid group id will refresh the page
        $condition = ['user_id' => Auth::user()->id, 'group_id' => $data['groupID']];

        DB::table('group_user')->where($condition)->delete();

        return view('manage-subscriptions')->with(['items' => $this->getItems()]);
    }

    public function add(Request $request)
    {
        $data = $request->all();

        DB::table('group_user')->insert(
            ['user_id' => Auth::user()->id, 'group_id' => $data['groupID'], 'status' => 'subscription']
        );

        return view('manage-subscriptions')->with(['items' => $this->getItems()]);
    }

    private function getItems()
    {
        //retrieve the information stored in the database
        $user = Auth::user();
        $items = $user->groups(Group::TYPE_SUBSCRIPTION)->get();

        return $items;
    }

}