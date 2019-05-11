<?php


namespace App\Tools\Permissions;


use App\Tools\Permission;

class ShowGroupExtendedPermission extends Permission
{
    static function has($id = null)
    {
        return Check::isLoggedIn() && Check::isMemberOfGroup($id);
    }

}