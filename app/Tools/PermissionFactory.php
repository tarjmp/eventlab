<?php


namespace App\Tools;


use App\Tools\Permissions\ShowGroupExtendedPermission;
use App\Tools\Permissions\ShowGroupPermission;

class PermissionFactory
{

    static function createShowGroup() {
        return new ShowGroupPermission();
    }

    static function createShowGroupExtended() {
        return new ShowGroupExtendedPermission();
    }

}