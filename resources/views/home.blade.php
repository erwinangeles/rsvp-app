@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="text-center py-5">
        <h1 class="display-4 mb-4">Welcome to Event RSVP</h1>
        <p class="lead mb-4">Create and share RSVP links with your friends. No login required to RSVP.</p>

        @auth
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-lg mb-5">Create an Event</a>

            @if ($events->isEmpty())
                <p class="text-muted">You haven’t created any events yet.</p>
            @else
                <div class="container mt-4">
                    <h4 class="text-start mb-3">Your Events</h4>
                    <div class="list-group text-start">
                        @foreach ($events as $event)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('events.show', $event) }}" class="fw-bold text-decoration-none">{{ $event->title }}</a>
                                        <div class="text-muted">{{ $event->description }}</div>
                                    </div>
                                    <div class="ms-3 d-flex gap-2">
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('events.destroy', $event) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Log In to Create Events</a>
        @endauth
    </div>
@endsection