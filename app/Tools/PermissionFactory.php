<?php


namespace App\Tools;


use App\Tools\Permissions\CreateEventForGroupPermission;
use App\Tools\Permissions\CreateEventPermission;
use App\Tools\Permissions\CreateGroupPermission;
use App\Tools\Permissions\DeleteEventPermission;
use App\Tools\Permissions\EditEventPermission;
use App\Tools\Permissions\EditGroupPermission;
use App\Tools\Permissions\EditProfilePermission;
use App\Tools\Permissions\LeaveGroupPermission;
use App\Tools\Permissions\RespondToEventPermission;
use App\Tools\Permissions\ShowEventExtendedPermission;
use App\Tools\Permissions\ShowEventPermission;
use App\Tools\Permissions\ShowGroupExtendedPermission;
use App\Tools\Permissions\ShowGroupPermission;
use App\Tools\Permissions\ShowGroupsPermission;
use App\Tools\Permissions\ShowHomeCalendarPermission;
use App\Tools\Permissions\SubscribeToGroupPermission;

class PermissionFactory
{

    static function createShowGroup() {
        return new ShowGroupPermission();
    }

    static function createShowGroupExtended() {
        return new ShowGroupExtendedPermission();
    }

    static function createCreateGroup() {
        return new CreateGroupPermission();
    }

    static function createEditGroup() {
        return new EditGroupPermission();
    }

    static function createSubscribeToGroup() {
        return new SubscribeToGroupPermission();
    }

    static function createLeaveGroup() {
        return new LeaveGroupPermission();
    }

    static function createShowEvent() {
        return new ShowEventPermission();
    }

    static function createShowEventExtended() {
        return new ShowEventExtendedPermission();
    }

    static function createCreateEventForGroup() {
        return new CreateEventForGroupPermission();
    }

    static function createEditEvent() {
        return new EditEventPermission();
    }

    static function createDeleteEvent() {
        return new DeleteEventPermission();
    }

    static function createRespondToEvent() {
        return new RespondToEventPermission();
    }

    static function createEditProfile() {
        return new EditProfilePermission();
    }

    static function createShowHomeCalendar() {
        return new ShowHomeCalendarPermission();
    }

    static function createShowGroups() {
        return new ShowGroupsPermission();
    }

    static function createCreateEvent() {
        return new CreateEventPermission();
    }


}