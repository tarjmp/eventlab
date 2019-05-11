<?php

namespace App\Tools;


use App;

abstract class Permission {


    // The following constants define the various types of permissions

    // PLEASE NOTE:
    // The required second parameter $id for the function call is specified in brackets:
    // [g] = Group-ID; [e] = Event-ID; [-] = None

    // Group permissions
    const showGroup         = 1;   //[g]  show general group info: name, description, events -> but not members or anything
    const showGroupExtended = 2;   //[g]  show extended group info: members and information that is not intended for guests

    const editGroup        = 3;   //[g]  edit a group: add members and edit group info
    const subscribeToGroup = 4;   //[g]  self-explanatory
    const leaveGroup       = 5;   //[g]  self-explanatory

    // Event permissions
    const showEvent           = 6;   //[e] show general event info: name, desc., location, time
    const showEventExtended   = 7;   //[e] show extended event info: chat, list, replies
    const createEventForGroup = 8;   //[g] create a new event for this group
    const editEvent           = 9;  //[e] edit all the event details
    const deleteEvent         = 10;  //[e] self-explanatory
    const respondToEvent      = 11;  //[e] reply to an event (accepted, declined, tentative)

    // Other permissions
    const editProfile      = 12;   //[-] edit the user profile
    const showHomeCalendar = 13;   //[-] show the personal calendar page
    const showGroups       = 14;   //[-] show the groups list
    const createGroup      = 15;   //[-]  create a new group
    const createEvent      = 16;   //[-] create a personal event


    // This is the permission check implementation. This function returns a boolean value that tells
    // if the permission should be granted.

    abstract static function has($id = null);

    // This is the strict implementation of the authorization check: If the request fails, the application
    // instantly terminates and serves a 403 access denied site
    public function check($id = null) {
        // throw exception if the required permission is not present
        self::has($id) || Navigator::die();
    }
}
