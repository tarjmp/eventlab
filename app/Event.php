<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    // status constants for event replies
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_TENTATIVE = 'tentative';
}
