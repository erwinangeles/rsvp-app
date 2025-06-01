<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RsvpItem;
use Illuminate\Http\Request;

class RsvpItemController extends Controller
{
    public function destroy(Event $event, RsvpItem $item)
    {
        if (session('rsvp_id') === $item->rsvp_id) {
            $item->delete();
            return back()->with('success', 'Item removed!');
        }
        abort(403);
    }
}