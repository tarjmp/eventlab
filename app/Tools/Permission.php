<?php

namespace App\Tools;


use App;
use App\Event;
use App\Group;

class Permission
{


    // The following constants define the various types of permissions

    // PLEASE NOTE:
    // The required second parameter $id for the function call is specified in brackets:
    // [g] = Group-ID; [e] = Event-ID; [-] = None

    // Group permissions
    const showGroup         = 1;   //[g]  show general group info: name, description, events -> but not members or anything
    const showGroupExtended = 2;   //[g]  show extended group info: members and information that is not intended for guests
    const createGroup       = 3;   //[-]  create a new group
    const editGroup         = 4;   //[g]  edit a group: add members and edit group info
    const subscribeToGroup  = 5;   //[g]  self-explanatory
    const leaveGroup        = 6;   //[g]  self-explanatory

    // Event permissions
    const showEvent           = 7;   //[e] show general event info: name, desc., location, time
    const showEventExtended   = 8;   //[e] show extended event info: chat, list, replies
    const createEventForGroup = 9;   //[g] create a new event for this group
    const editEvent           = 10;  //[e] edit all the event details
    const deleteEvent         = 11;  //[e] self-explanatory
    const respondToEvent      = 12;  //[e] reply to an event (accepted, declined, tentative)

    // Other permissions
    const editProfile      = 13;   //[-] edit the user profile
    const showHomeCalendar = 14;   //[-] show the personal calendar page
    const createEvent      = 15;   //[-] create a personal event


    // This is the permission check implementation. This function returns a boolean value that tells
    // if the permission should be granted.

    static function has($permission, $id = null)
    {

        // ======================== CAUTION - CRITICAL ZONE! ==========================
        // This is the actual authorization logic. Please edit this function carefully!
        // ============================================================================

        try {
            switch ($permission) {

                case self::showGroup:
                    return Check::isPublicGroup($id) || (Check::isLoggedIn() && Check::isMemberOfGroup($id));

                case self::showGroupExtended:
                    return Check::isLoggedIn() && Check::isMemberOfGroup($id);

                case self::createGroup:
                    return Check::isLoggedIn();

                case self::editGroup:
                    return Check::isLoggedIn() && Check::isMemberOfGroup($id);

                case self::subscribeToGroup:
                    return Check::isLoggedIn() && Check::isPublicGroup($id) && !Check::isMemberOfGroup($id);

                case self::leaveGroup:
                    return Check::isLoggedIn() && Check::isMemberOfGroup($id);

                case self::showEvent:
                    return Check::isPublicEvent($id) || (Check::isLoggedIn() && Check::isMemberOfEvent($id));

                case self::showEventExtended:
                    return Check::isLoggedIn() && Check::isMemberOfEvent($id);

                case self::createEventForGroup:
                    return Check::isLoggedIn() && Check::isMemberOfGroup($id);

                case self::editEvent:
                    return Check::isLoggedIn() && Check::isMemberOfEvent($id);

                case self::deleteEvent:
                    return Check::isLoggedIn() && Check::isMemberOfEvent($id);

                case self::respondToEvent:
                    return Check::isLoggedIn() && (Check::isPublicGroup($id) || Check::isMemberOfEvent($id));

                case self::editProfile:
                    return Check::isLoggedIn();

                case self::showHomeCalendar:
                    return Check::isLoggedIn();

                case self::createEvent:
                    return Check::isLoggedIn();

                default:
                    // Unknown permission requested - something went terribly wrong...
                    self::fail();
            }

        } catch (\Exception $e) {
            // In case any permission check throws an exception, the permission
            // will be denied for security reasons as this should never happen...
            return false;
        }
        return false;
    }


    // This is the strict implementation of the authorization check: If the request fails, the application
    // instantly terminates and serves a 403 access denied site
    static function check($permission, $id = null)
    {
        // throw exception if the required permission is not present
        self::has($permission, $id) || self::fail();
    }

    // This function raises a 403 unauthorized exception and stops further script execution
    static private function fail()
    {
        if (!Check::isLoggedIn()) {
            // give the user a chance to login if he is not logged in
            abort(302, '', ['location' => route('login')]);
        } else {
            abort(403, __('auth.access_denied'));
        }
    }
}
