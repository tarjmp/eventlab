<?php

namespace App\Http\Controllers;

use App\Event;
use App\Tools\Permission;
use App\Tools\Query;

class HomeController extends Controller {

    public function index() {
        // require home screen permission
        Permission::check(Permission::showHomeCalendar);

        $events = Query::getUserEvents();

        return view('home')->with('events', $events);
    }
}
