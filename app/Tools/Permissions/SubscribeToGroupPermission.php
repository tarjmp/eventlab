<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class SubscribeToGroupPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isLoggedIn() && Check::isPublicGroup($id) && !Check::isMemberOfGroup($id) && !Check::isSubscriberOfGroup($id);
    }

}