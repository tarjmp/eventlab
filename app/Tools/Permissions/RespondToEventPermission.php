<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class RespondToEventPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isLoggedIn() && (Check::isPublicEvent($id) || Check::isMemberOfEvent($id));
    }

}