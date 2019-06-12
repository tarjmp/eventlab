<?php

namespace App\Http\Controllers;

use App\Event;
use App\Item;
use App\Tools\PermissionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function add(Request $request, $id)
    {

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);


        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'nullable|string|max:255',
            'user' => 'nullable|string|max:100',
        ]);

        // Update the event with passed data
        $item = new Item();
        $item->event_id = $id;
        $item->name = $data['name'];
        $item->amount = $data['amount'];
        if (isset($data['user'])) {
            $item->user_id = Auth::id();
        } else {
            $item->user_id = null;
        }
        $item->save();

        return redirect(route('listEdit', $id))->with(['item-added' => true]);
    }

    public function bring(Request $request, $id)
    {
        $data = $request->all();

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);

        $item = Event::findOrFail($id)->items()->where('id', '=', $data['item'])->first();

        if ($item) {
            // assign if nobody else is assigned
            if (isset($data['user']) && !$item->user) {
                $item->user_id = Auth::id();
            } // unassign if one self is assigned
            else if (!isset($data['user']) && $item->user && $item->user->id == Auth::id()) {
                $item->user_id = null;
            }

            $item->save();
        }

        return redirect(route('list', $id));
    }

    public function delete(Request $request, $id)
    {
        $data = $request->all();

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);

        // delete item
        $item = Event::findOrFail($id)->items()->where('id', '=', $data['item'])->first();
        if ($item) {
            $item->delete();
        }

        return redirect(route('listEdit', $id));
    }

    private function getItems($id)
    {
        //retrieve the information stored in the database
        $event = Event::findOrFail($id);
        return $event->items()->orderBy('name')->get();
    }

}
