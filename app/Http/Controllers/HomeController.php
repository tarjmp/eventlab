<?php

namespace App\Http\Controllers;

use App\Tools\Permission;

class HomeController extends Controller {

    public function index() {
        // require home screen permission
        Permission::check(Permission::showHomeCalendar);

        return view('home');
    }
}
