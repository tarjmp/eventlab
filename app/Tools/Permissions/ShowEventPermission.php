<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class ShowEventPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isPublicEvent($id) || (Check::isLoggedIn() && Check::isMemberOfEvent($id));
    }

}