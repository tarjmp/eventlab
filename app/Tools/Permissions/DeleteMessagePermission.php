<?php


namespace App\Tools\Permissions;


use App\Tools\Check;
use App\Tools\Permission;

class DeleteMessagePermission extends Permission
{
    public function has($id = null)
    {
        return Check::isMyMessage($id);
    }

}