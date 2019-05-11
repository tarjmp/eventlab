<?php


namespace App\Tools;


use App\Tools\Permissions\CreateGroupPermission;
use App\Tools\Permissions\EditGroupPermission;
use App\Tools\Permissions\LeaveGroupPermission;
use App\Tools\Permissions\ShowGroupExtendedPermission;
use App\Tools\Permissions\ShowGroupPermission;
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


}