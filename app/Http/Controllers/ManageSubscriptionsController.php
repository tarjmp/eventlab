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
        //TODO delete subscription

        return view('manage-subscriptions')->with(['items' => $this->getItems()]);
    }

    private function getItems()
    {
        //retrieve the information stored in the database
        $user = Auth::user();
        $items = $user->groups(Group::TYPE_SUBSCRIPTION);

        return $items;
    }

}