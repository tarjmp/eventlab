<?php

namespace App\Http\Controllers;

use App\Tools\Permission;
use App\Tools\PermissionFactory;
use App\Tools\Query;

class HomeController extends Controller {

    public function index() {
        // require home screen permission
        PermissionFactory::createShowHomeCalendar()->check();

        // get all events for this user
        $events = Query::getUserEvents();

        return view('home')->with('events', $events);
    }
}
