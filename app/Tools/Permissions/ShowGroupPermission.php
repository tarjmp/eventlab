<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class ShowGroupPermission extends Permission
{

    static function has($permission, $id = null)
    {
        return Check::isPublicGroup($id) || (Check::isLoggedIn() && Check::isMemberOfGroup($id));
    }
}