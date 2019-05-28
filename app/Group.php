<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function subscribed()
    {
        //User cannot be subscribed when he is not logged in
        if (!isset(Auth::user()->id)) {
            return false;
        }

        $condition = ['user_id' => Auth::user()->id, 'group_id' => $this->id];

        if (DB::table('group_user')->where($condition)->exists()) {
            return true;
        }
        return false;
    }
}
