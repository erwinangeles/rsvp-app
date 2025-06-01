@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="card-title">Create a New Event</h3>
            <form method="POST" action="{{ route('events.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Event Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </form>
        </div>
    </div>
@endsection