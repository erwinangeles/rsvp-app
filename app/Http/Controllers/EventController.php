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
            'name' => 'required_if:rsvp_id,null',
            'item' => 'required',
            'phone' => 'required',
        ]);

        $rsvp = $event->rsvps()->firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => $request->name,
                'item' => $request->item,
                'phone' => $request->phone,
            ]
        );

        if (!$rsvp->wasRecentlyCreated) {
            $rsvp->update([
                'item' => $request->item,
                'name' => $request->name ?? $rsvp->name,
            ]);
        }

        session(['rsvp_id' => $rsvp->id]);

        return redirect()->route('events.show', $event)->with('success', 'RSVP saved!');
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

    public function edit(Event $event, Rsvp $rsvp)
    {
        abort_if(session('rsvp_id') !== $rsvp->id, 403);
        return view('events.edit', compact('event', 'rsvp'));
    }

    public function update(Request $request, Event $event, Rsvp $rsvp)
    {
        abort_if(session('rsvp_id') !== $rsvp->id, 403);

        $request->validate([
            'name' => 'required',
            'item' => 'required',
            'phone' => 'required',
        ]);

        $rsvp->update($request->only('name', 'item', 'phone'));

        return redirect()->route('events.show', $event)->with('success', 'RSVP updated!');
    }

    public function destroy(Event $event, Rsvp $rsvp)
    {
        abort_if(session('rsvp_id') !== $rsvp->id, 403);
        $rsvp->delete();
        session()->forget('rsvp_id');

        return redirect()->route('events.show', $event)->with('success', 'RSVP deleted.');
    }
}