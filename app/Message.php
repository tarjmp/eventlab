<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{

    use SoftDeletes;

    // The event the message belongs to
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // The user that wrote this message
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}