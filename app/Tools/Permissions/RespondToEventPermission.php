<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class RespondToEventPermission extends Permission
{
    static function has($id = null)
    {
        return Check::isLoggedIn() && (Check::isPublicEvent($id) || Check::isMemberOfEvent($id));
    }

}