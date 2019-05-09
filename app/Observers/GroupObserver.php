<?php

namespace App\Observers;

use App\Group;

class GroupObserver
{
    // This function is triggered when a group has to be deleted.
    // It will remove all events associated with this group as well as subscriber / membership information
    public function deleting(Group $group) {

        // remove all memberships and subscriptions
        $group->members()->detach();
        $group->subscribers()->detach();

        // call delete method for each event individually to trigger the corresponding cleanup (delete items, messages & replies)
        $group->events->each(function($event) {
            $event->delete();
        });

    }
}
