<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'uuid' => Str::uuid(),
        ]);

        return redirect()->route('events.show', $event);
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function rsvp(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'item' => 'required',
        ]);

        Rsvp::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'item' => $request->item,
        ]);

        return redirect()->route('events.show', $event);
    }
}