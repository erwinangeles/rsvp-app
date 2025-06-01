@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="text-center py-5">
        <h1 class="display-4 mb-4">Welcome to Event RSVP</h1>
        <p class="lead mb-5">Create and share RSVP links with your friends. No login required to RSVP.</p>

        @auth
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('events.create') }}" class="btn btn-primary btn-lg">Create an Event</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-lg">Log Out</button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Log In to Create Events</a>
        @endauth
    </div>
@endsection