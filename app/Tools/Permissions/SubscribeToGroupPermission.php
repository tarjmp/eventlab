<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class SubscribeToGroupPermission extends Permission
{
    static function has($permission, $id = null)
    {
        return Check::isLoggedIn() && Check::isPublicGroup($id) && !Check::isMemberOfGroup($id);
    }

}