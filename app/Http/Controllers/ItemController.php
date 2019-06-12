<?php

namespace App\Http\Controllers;

use App\Event;
use App\Item;
use App\Tools\PermissionFactory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    //Show the editable form of the item
    public function edit($id)
    {
        $item = Item::findOrFail($id);

        //check if the user is allow to edit this item
        PermissionFactory::createEditEvent()->check($item->event_id);

        return view('item')->with(['item' => $item]);
    }

    public function save(Request $request, $id)
    {
        $data = $request->all();

        $request->validate(['name' => 'required|string|min:1']);

        $item = Item::findOrFail($id);

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($item->event_id);

        $item['name'] = $data['name'];
        $item['amount'] = $data['amount'];

        $item->save();

        return redirect(route('listEdit', $item->event_id));

    }
}
