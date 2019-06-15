<?php

namespace App\Http\Controllers;

use App\Item;
use App\Tools\PermissionFactory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    //Show the editable form of the item
    public function edit($id)
    {
        $item = Item::findOrFail($id);

        //check if the user is allow to edit this item
        PermissionFactory::createEditEvent()->check($item->event_id);

        //give user name when brought by someone else
        if ($item->user_id != null && $item->user_id != Auth::id()){
            $user = User::findOrFail($item->user_id);
            return view('item')->with(['item' => $item, 'user_name' => $user->name()]);
        }

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
        // assign if nobody else is assigned
        if (isset($data['user']) && !$item->user) {
            $item->user_id = Auth::id();
        } // unassign if one self is assigned
        else if (!isset($data['user']) && $item->user && $item->user->id == Auth::id()) {
            $item->user_id = null;
        }

        $item->save();

        return redirect(route('listEdit', $item->event_id));

    }
}
