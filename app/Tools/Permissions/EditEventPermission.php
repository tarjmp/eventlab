<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class EditEventPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isLoggedIn() && Check::isMemberOfEvent($id);
    }

}