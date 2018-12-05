<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    // The event the item belongs to
    public function event() {
        return $this->belongsTo(Event::class);
    }

    // The user that will bring this particular item
    public function user() {
        return $this->belongsTo(User::class);
    }
}