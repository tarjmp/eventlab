<?php

namespace App\Http\Controllers;

use App\Tools\PermissionFactory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $oRequest) {

        PermissionFactory::createSearch()->check();

        // check for a valid event id and text message
        $oRequest->validate([
            'term' => 'required|string|max:255',
        ]);

        $aData = $oRequest->all();

        return view('search', ['search' => $aData['term'], 'results' => ['7']]);
    }
}
