<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // check for permission to edit the event
        //PermissionFactory::createEditEvent()->check();

        $data = $request->all();
        // never trust any user input
        $this->validateInput($data);

        $id = $data['eventID'];

        // Update the event with passed data
        $item = new Item();
        $item->event_id = $id;
        $item->name = $data['name'];
        $item->amount = $data['amount'];
        if (isset($data['user'])) $item->user_id = Auth::user()->id;
        else $item->user_id = null;

        $item->save();

        return view('what-to-bring-list-show')->with(['eventID' => $id, 'updated' => true, 'items' => $this->getItems($id)]);
    }

    private function getItems()
    {
        //retrieve the information stored in the database
        $user = Auth::user();
        $items = $user->groups(Group::TYPE_SUBSCRIPTION);

        foreach($items as $item)
        {
            echo $item;
        }

        die();

        return $items;
    }

}
