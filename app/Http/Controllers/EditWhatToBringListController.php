<?php

namespace App\Http\Controllers;

use App\Event;
use App\Tools\PermissionFactory;
use App\User;

class EditWhatToBringListController extends Controller
{

    //Show the form of the What-to-bring list
    public function show($id)
    {

        //check if the user is allow to see this list
        PermissionFactory::createShowEventExtended()->check($id);

        //retrieve the information stored in the database
        $event = Event::findOrFail($id);
        $items = $event->items;

        foreach ($items as $item) {
            if (isset($item->user_id)) {
                $user = User::find($item->user_id);
                $item['full_name'] = $user->name();
            } else {
                $item['full_name'] = null;
            }
        }

        return view('what_to_bring_list_show')->with(['items' => $items]);
    }

}
