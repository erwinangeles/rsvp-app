<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = ['event_id', 'name', 'phone', 'item'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}