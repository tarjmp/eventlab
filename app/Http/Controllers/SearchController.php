<?php

namespace App\Http\Controllers;

use App\Tools\PermissionFactory;
use App\Tools\Query;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $oRequest)
    {
        // check for a valid search term
        $oRequest->validate([
            'term' => 'required|string|max:255',
        ]);

        $aData = $oRequest->all();

        // convert string to lower for case insensitive comparison
        $sTerm = strtolower($aData['term']);

        // transform search term into regex
        $sTerm = '.*(' . $this->escapeRegex($sTerm) . ').*';

        // search all events: title and description by using regex
        $cEvents = $this->filterResults(Query::getAllAccessibleEvents(), $sTerm);

        // search all events: title and description by using regex
        $cGroups = $this->filterResults(Query::getAllAccessibleGroups(), $sTerm);

        // number of total results
        $iNumResults = count($cEvents) + count($cGroups);

        return view('search', ['search' => $aData['term'], 'num_results' => $iNumResults, 'events' => $cEvents, 'groups' => $cGroups]);
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

    private function filterResults($oAllResults, $sTerm)
    {
        return $oAllResults->where(function ($oQuery) use ($sTerm) {
            $oQuery->where('name', '~*', $sTerm)->orWhere('description', '~*', $sTerm)->orderBy('name')->get();
        })->orderBy('name')->get();
    }
}
