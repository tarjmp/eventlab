<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{

    // status constants for event replies
    const STATUS_ACCEPTED  = 'accepted';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_TENTATIVE = 'tentative';

    // Returns the author to an event
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // The group that organizes the event
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // All items that are needed for the event
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // All messages in the chat of the event
    public function messages()
    {
        return $this->hasMany(Message::class)->withTrashed()->orderBy('id');
    }

    // All replies to this event
    public function replies()
    {
        return $this->belongsToMany(User::class, 'event_replies')->withPivot('status')->withTimestamps();
    }

    // My reply to this event. This will return Event::STATUS_ACCEPTED, etc.
    public function myReply()
    {
        $reply = $this->replies()->where('id', '=', Auth::id())->first();
        if ($reply) {
            return $reply->pivot->status;
        }
        return null;
    }

    // Returns if an event has been replied to or not
    public function hasEventReply()
    {
        return $this->myReply() == null;
    }
}
