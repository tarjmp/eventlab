<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class EditGroupPermission extends Permission
{
    static function has($id = null)
    {
        return Check::isLoggedIn() && Check::isMemberOfGroup($id);
    }

}