<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class UnsubscribeFromGroupPermission extends Permission
{
    public function has($id = null)
    {
        return Check::isLoggedIn() && Check::isPublicGroup($id) && Check::isSubscriberOfGroup($id);
    }

}