<?php

namespace App\Tools;


use App;

abstract class Permission
{


    // The following types of permissions exist:

    // PLEASE NOTE:
    // The required second parameter $id for the function call is specified in brackets:
    // [g] = Group ID; [e] = Event ID; [m] = Message ID; [-] = None

    // +++ Group permissions +++

    // showGroup              [g]  Show general group info: name, description, events -> but not members or anything
    // showGroupExtended      [g]  Show extended group info: members and information that is not intended for guests
    // editGroup              [g]  Edit a group: add members and edit group info
    // subscribeToGroup       [g]  Subscribe to a particular group
    // leaveGroup             [g]  Leave a particular group

    // +++ Event permissions +++
    // showEvent              [e]  Show general event info: name, desc., location, time
    // showEventExtended      [e]  Show extended event info: chat, list, replies
    // createEventForGroup    [g]  Create a new event for this group
    // editEvent              [e]  Edit all event details
    // deleteEvent            [e]  Delete an event
    // respondToEvent         [e]  Reply to an event (accepted, declined, tentative)
    // deleteMessage          [m]  Delete a chat message

    // +++ Other permissions +++
    // editProfile            [-]  Edit the user profile
    // showHomeCalendar       [-]  Show the personal calendar page
    // showGroups             [-]  Show the groups list
    // createGroup            [-]  Create a new group
    // createEvent            [-]  Create a personal event
    // search                 [-]  Use the site's search functionality


    // This is the permission check. This function returns a boolean value that tells
    // if the permission should be granted. The implementation can be found in each derived class.

    public abstract function has($id = null);

    // This is the strict implementation of the authorization check: If the request fails, the application
    // instantly terminates and serves a 403 access denied site

    public function check($id = null)
    {
        // throw exception if the required permission is not present
        $this->has($id) || Navigator::die();
    }
}
