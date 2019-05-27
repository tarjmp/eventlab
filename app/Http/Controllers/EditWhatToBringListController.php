<?php

namespace App\Http\Controllers;

use App\Event;
use App\Item;
use App\Tools\PermissionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class EditWhatToBringListController extends Controller
{

    //Show the read-only form of the What-to-bring list
    public function show($id)
    {

        //check if the user is allow to see this list
        PermissionFactory::createShowEventExtended()->check($id);

        return view('what-to-bring-list-show')->with(['eventID' => $id, 'items' => $this->getItems($id)]);
    }

    //Show the editable form of the What-to-bring list
    public function edit($id)
    {
        //check if the user is allow to see this list
        PermissionFactory::createShowEventExtended()->check($id);

        return view('what-to-bring-list-edit')->with(['eventID' => $id, 'items' => $this->getItems($id)]);
    }

    public function add(Request $request)
    {
        $data = $request->all();
        // never trust any user input
        $this->validateInput($data);

        $id = $data['eventID'];

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);

        // Update the event with passed data
        $item = new Item();
        $item->event_id = $id;
        $item->name = $data['name'];
        $item->amount = $data['amount'];
        if (isset($data['user'])) {
            $item->user_id = Auth::user()->id;
        } else {
            $item->user_id = null;
        }
        $item->save();

        return view('what-to-bring-list-show')->with(['eventID' => $id, 'updated' => true, 'items' => $this->getItems($id)]);
    }

    public function bring(Request $request)
    {

        $data = $request->all();

        $id = $data['eventID'];

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);

        $item = Item::findOrFail($id);

        if (isset($data['user'])){
            $item->user_id = Auth::user()->id;
        } else {
            $item->user_id = null;
        }

        $item->save();

        return view('what-to-bring-list-show')->with(['eventID' => $id, 'updated' => true, 'items' => $this->getItems($id)]);
    }

    private function getItems($id)
    {
        //retrieve the information stored in the database
        $event = Event::findOrFail($id);
        $items = $event->items;

        foreach ($items as $item) {
            if (isset($item->user)) {
                $item['full_name'] = $item->user->name();
            } else {
                $item['full_name'] = null;
            }
        }
        return $items;
    }


    //  validateInput
    //
    //  This function determines whether the given data for an event is valid.
    //
    private function validateInput(array $data)
    {
        // validate all input data against the following rules
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
        ]);

        $validator->validate();
    }

}
