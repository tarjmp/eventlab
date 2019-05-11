<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class ShowGroupPermission extends Permission
{

    public function has($id = null)
    {
        return Check::isPublicGroup($id) || (Check::isLoggedIn() && Check::isMemberOfGroup($id));
    }
}