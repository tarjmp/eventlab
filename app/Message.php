<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    // The event the message belongs to
    public function event() {
        return $this->belongsTo(Event::class);
    }

    // The user that wrote this message
    public function user() {
        return $this->belongsTo(User::class);
    }
}