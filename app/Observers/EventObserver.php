<?php

namespace App\Observers;

use App\Event;

class EventObserver
{
    // This function is triggered when an event has to be deleted.
    // It will remove all foreign key constraints, i.e. all items, messages and replies associated with this event
    public function deleting(Event $event) {

        $event->items()->delete();
        $event->messages()->forceDelete();
        $event->replies()->detach();
    }
}
