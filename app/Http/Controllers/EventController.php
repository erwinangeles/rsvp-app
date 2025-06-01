<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'banner_image' => 'nullable|image|max:2048',
            'meta_image' => 'nullable|image|max:2048',
        ]);

        $bannerImagePath = null;
        $metaImagePath = null;

        if ($request->hasFile('banner_image')) {
            $bannerImagePath = $request->file('banner_image')->store('images', 's3');
            $bannerImagePath = Storage::disk('s3')->url($bannerImagePath);
        }

        if ($request->hasFile('meta_image')) {
            $metaImagePath = $request->file('meta_image')->store('images', 's3');
            $metaImagePath = Storage::disk('s3')->url($metaImagePath);
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'uuid' => Str::lower(Str::random(8)),
            'user_id' => auth()->id(),
            'meta_image' => $bannerImagePath,
            'meta_image' => $metaImagePath,
        ]);
        return redirect()->route('events.show', $event);
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required',
            'banner_image' => 'nullable|image|max:2048',
            'meta_image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('images', 's3');
            $data['banner_image'] = Storage::disk('s3')->url($path);
        }

        if ($request->hasFile('meta_image')) {
            $path = $request->file('meta_image')->store('images', 's3');
            $data['meta_image'] = Storage::disk('s3')->url($path);
        }

        $event->update($data);

        return redirect()->route('events.show', $event)->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect('/')->with('success', 'Event deleted!');
    }

    public function show(Event $event)
    {
        $event->load('rsvps.items');

        if (auth()->check() && $event->user_id === auth()->id()) {
            $user = auth()->user();

            if ($user->phone) {
                $rsvp = $event->rsvps()->firstOrCreate(
                    ['phone' => $user->phone],
                    ['name' => $user->name]
                );

                session(['rsvp_id' => $rsvp->id]);
            }
        }

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
        $rules = [
            'phone' => 'required',
        ];

        if (!session()->has('rsvp_id')) {
            $rules['name'] = 'required';
        } else {
            $rules['item'] = 'required'; // only required when user is already RSVP’d
        }

        $request->validate($rules);

        $rsvp = Rsvp::firstOrCreate(
            ['event_id' => $event->id, 'phone' => $request->phone],
            ['name' => $request->name]
        );

        if ($request->filled('item')) {
            $rsvp->items()->create(['item' => $request->item]);
        }

        session(['rsvp_id' => $rsvp->id]);

        return redirect()->route('events.show', $event)->with('success', 'Thanks for RSVPing!');
    }
}