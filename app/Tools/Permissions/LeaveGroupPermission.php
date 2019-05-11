<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class LeaveGroupPermission extends Permission
{
    static function has($permission, $id = null)
    {
        return Check::isLoggedIn() && Check::isMemberOfGroup($id);
    }

}