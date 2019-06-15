<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    const TYPE_MEMBERSHIP = 'membership';
    const TYPE_SUBSCRIPTION = 'subscription';

    // All events this group organizes
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // The members of this group
    public function members()
    {
        return $this->belongsToMany(User::class)->where('status', Group::TYPE_MEMBERSHIP)->withTimestamps();
    }

    // The subscribers of this group
    public function subscribers()
    {
        return $this->belongsToMany(User::class)->where('status', Group::TYPE_SUBSCRIPTION)->withTimestamps();
    }
}
