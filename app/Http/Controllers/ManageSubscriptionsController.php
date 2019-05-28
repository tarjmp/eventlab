<?php

namespace App\Http\Controllers;

use App\Group;
use App\Tools\PermissionFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageSubscriptionsController extends Controller
{

    //Show the read-only form of the What-to-bring list
    public function show()
    {
        PermissionFactory::createShowGroups();
        return view('manage-subscriptions')->with(['groups' => $this->getItems()]);
    }

    public function remove(Request $request, $id)
    {
        PermissionFactory::createUnsubscribeFromGroup()->check($id);
        $group = Group::findOrFail($id);
        $group->subscribers()->detach(Auth::user());

        return redirect(route('showSubscriptions'));
    }

    public function add(Request $request, $id)
    {
        PermissionFactory::createSubscribeToGroup()->check($id);

        $group = Group::findOrFail($id);
        $group->subscribers()->attach(Auth::user(), ['status' => 'subscription']);

        return redirect(route('showSubscriptions'));
    }

    private function getItems()
    {
        //retrieve the information stored in the database
        return Auth::user()->groups(Group::TYPE_SUBSCRIPTION)->get();
    }

}
