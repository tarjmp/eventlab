<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class ShowGroupsPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isLoggedIn();
    }

}