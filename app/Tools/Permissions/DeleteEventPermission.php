<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class DeleteEventPermission extends Permission
{
    static function has($permission, $id = null)
    {
        return Check::isLoggedIn() && Check::isMemberOfEvent($id);
    }

}