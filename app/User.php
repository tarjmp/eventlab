<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = ['email', 'password', 'first_name', 'last_name', 'date_of_birth', 'location',];
    protected $hidden   = ['password', 'remember_token',];

    // All events a user has created
    public function events() {
        return $this->hasMany(Event::class, 'created_by');
    }

    // All groups the user is a member in
    public function groups() {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    // All items the user brought or will bring
    public function items() {
        return $this->hasMany(Item::class);
    }

    // All messages the user sent
    public function messages() {
        return $this->hasMany(Message::class);
    }

    // All events the user replied to
    public function replies() {
        return $this->belongsToMany(Event::class, 'event_replies')->withTimestamps();
    }
}
