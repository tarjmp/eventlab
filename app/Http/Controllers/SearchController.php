<?php

namespace App\Http\Controllers;

use App\Tools\PermissionFactory;
use App\Tools\Query;
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

        // convert string to lower for case insensitive comparison
        $sTerm = strtolower($aData['term']);

        // transform search term into regex
        $sTerm = '.*(' . $this->escapeRegex($sTerm) . ').*';

        $iNumResults = 0;

        // search all events: title and description by using regex
        $cEvents = Query::getUserEvents(true)->where('name', '~*', $sTerm)->orWhere('description', '~*', $sTerm)->orderBy('name')->get();
        $iNumResults += count($cEvents);

        return view('search', ['search' => $aData['term'], 'num_results' => $iNumResults, 'events' => $cEvents]);
    }

    private function escapeRegex(string $sStatement, string $char = '\\')
    {
        // escape special regex characters
        $sStatement = str_replace(
            ['(', ')', '{', '}', '[', ']', '|', '*', '.', '?', '\\'],
            ['\\(', '\\)', '\\{', '\\}', '\\[', '\\]', '[|]', '[*]', '[.]', '[?]', '\\\\'],
            $sStatement
        );

        // replace all spaces with pipe -> only one of the terms needs to be found
        $sStatement = str_replace(' ', '|', $sStatement);
        return $sStatement;
    }
}
