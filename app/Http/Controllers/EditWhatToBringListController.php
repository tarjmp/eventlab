<?php

namespace App\Http\Controllers;

use App\Event;
use App\Item;
use App\Tools\PermissionFactory;

class EditWhatToBringListController extends Controller
{

    //Show the form of the What-to-bring list
    public function show($id)
    {

        //check if the user is allow to see this list
        PermissionFactory::createShowEventExtended()->check($id);

        //retrieve the information stored in the database
        $items = Item::where('event_id', $id)->get();

        return view('what_to_bring_list_show')->with(['items' => $items]);
    }

}
