<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class ShowGroupExtendedPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isLoggedIn() && Check::isMemberOfGroup($id);
    }

}