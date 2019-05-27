<?php

namespace App\Http\Controllers;

use App\Event;
use App\Tools\PermissionFactory;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {

        //check if the user is allow to see this list
        //PermissionFactory::createShowEventExtended()->check($id);



        return view('what-to-bring-list-show')->with(['updated' => true, 'items' => $this->getItems($id)]);
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

}
