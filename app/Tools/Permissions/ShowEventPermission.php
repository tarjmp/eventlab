<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class ShowEventPermission extends Permission
{
    static function has($permission, $id = null)
    {
        return Check::isPublicEvent($id) || (Check::isLoggedIn() && Check::isMemberOfEvent($id));
    }

}