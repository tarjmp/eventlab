<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class CreateEventPermission extends Permission
{
    static function has($permission, $id = null)
    {
        return Check::isLoggedIn();
    }

}