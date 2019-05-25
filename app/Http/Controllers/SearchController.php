<?php

namespace App\Http\Controllers;

use App\Tools\PermissionFactory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {

        PermissionFactory::createSearch()->check();

        return view('search', ['search' => 'Bananenkuchen', 'results' => ['7']]);
    }
}
