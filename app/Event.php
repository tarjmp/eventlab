<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    // status constants for event replies
    const STATUS_ACCEPTED  = 'accepted';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_TENTATIVE = 'tentative';

    // Returns the author to an event
    public function author() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // The group that organizes the event
    public function group() {
        return $this->belongsTo(Group::class);
    }

    // All items that are needed for the event
    public function items() {
        return $this->hasMany(Item::class);
    }

    // All messages in the chat of the event
    public function messages() {
        return $this->hasMany(Message::class)->withTrashed()->orderBy('id');
    }

    // All replies to this event
    public function replies() {
        return $this->belongsToMany(User::class, 'event_replies')->withTimestamps();
    }
}
