<?php

namespace App\Tools;

use App\Event;
use Illuminate\Support\Facades\Auth;

class Query {

    // Retrieves all events for one user, this includes:
    //  - private events
    //  - memberships
    //  - subscriptions
    public static function getUserEvents() {

        // return all private events
        return Event::whereNull('group_id')->where('created_by', Auth::user()->id)
            // combined with all subscriptions
            ->orWhere(function ($query) {
                $query->whereHas('group', function ($query) {
                    $query->where('public', true)->whereHas('subscribers', function ($query) {
                        $query->where('id', Auth::user()->id);
                    });
                });
            })
            // combined with all memberships
            ->orWhere(function ($query) {
                $query->whereHas('group', function ($query) {
                    $query->whereHas('members', function ($query) {
                        $query->where('id', Auth::user()->id);
                    });
                });
            })->orderBy('start_time')->get();
    }
}