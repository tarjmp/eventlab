<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    // All events this group organizes
    public function events() {
        return $this->hasMany(Event::class);
    }

    // The members of this group
    public function members() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}