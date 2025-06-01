<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\RsvpItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'uuid' => Str::uuid(),
        ]);
        return redirect()->route('events.show', $event);
    }

    public function show(Event $event)
    {
        $event->load('rsvps.items');
        return view('events.show', compact('event'));
    }

    public function verifyPhone(Request $request, Event $event)
    {
        $rsvp = $event->rsvps()->where('phone', $request->phone)->first();
        if ($rsvp) {
            session(['rsvp_id' => $rsvp->id]);
            return response()->json(['found' => true]);
        }
        return response()->json(['found' => false]);
    }

    public function rsvp(Request $request, Event $event)
    {
        $request->validate([
            'phone' => 'required',
            'item' => 'required',
            'name' => 'required_without:rsvp_id',
        ]);

        $request->validate($rules);

        $rsvp = Rsvp::firstOrCreate(
            ['event_id' => $event->id, 'phone' => $request->phone],
            ['name' => $request->name]
        );

        $rsvp->items()->create(['item' => $request->item]);

        session(['rsvp_id' => $rsvp->id]);

        return redirect()->route('events.show', $event)->with('success', 'Thanks for RSVPing!');
    }
}