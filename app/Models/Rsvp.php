<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rsvp extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'name', 'phone'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function items()
    {
        return $this->hasMany(RsvpItem::class);
    }
}