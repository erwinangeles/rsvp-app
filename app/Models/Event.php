<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'uuid'];

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}