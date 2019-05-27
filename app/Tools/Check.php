<?php

namespace App\Tools;

use App\Event;
use App\Group;
use App\Message;
use Illuminate\Support\Facades\Auth;

// This class provides functionality for permission checks.
//
// Each function returns a boolean value and must be secured against failure.

class Check
{

    // Returns true if the user is logged in
    public static function isLoggedIn()
    {
        return boolval(Auth::check());
    }

    // Determines whether the user is member of a particular group
    public static function isMemberOfGroup($id)
    {
        $group = Group::find($id);
        if ($group) {
            return boolval($group->members()->find(Auth::user()->id));
        }
        return false;
    }

    // Determines whether the user can access a particular event
    public static function isMemberOfEvent($id)
    {
        $event = Event::find($id);
        if ($event) {
            return self::isMyPrivateEvent($id) || ($event->group != null && self::isMemberOfGroup($event->group->id));
        }
        return false;
    }

    // Determines whether an event is the private event OF THE CURRENTLY LOGGED-IN USER
    // Private events do not have a group assigned and are only visible to the creator
    public static function isMyPrivateEvent($id)
    {
        $event = Event::find($id);
        if ($event) {
            return $event->group == null && $event->created_by == Auth::id();
        }
        return false;
    }

    // Returns true for public groups
    public static function isPublicGroup($id)
    {
        $group = Group::find($id);
        if ($group) {
            return boolval($group->public);
        }
        return false;
    }

    // Returns true for public events
    public static function isPublicEvent($id)
    {
        $event = Event::find($id);
        if ($event && $event->group) {
            return boolval($event->group->public);
        }
        return false;
    }

    // Returns true for my messages in any of the user's events (membership) created by the user himself
    public static function isMyMessage($id)
    {
        $message = Message::find($id);
        // message must exist and user needs to be member of event
        if ($message && $message->event && self::isMemberOfEvent($message->event->id)) {
            // and message needs to be from this user
            if ($message->user_id == Auth::id()) {
                return true;
            }
        }
        return false;
    }
}