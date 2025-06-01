<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsvpItem extends Model
{
    use HasFactory;

    protected $fillable = ['rsvp_id', 'item'];

    public function rsvp()
    {
        return $this->belongsTo(Rsvp::class);
    }
}