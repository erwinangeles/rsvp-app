<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id', 'description', 'uuid'];

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}