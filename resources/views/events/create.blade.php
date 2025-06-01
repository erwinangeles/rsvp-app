@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="card-title">Create a New Event</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Event Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="banner_image" class="form-label">Banner Image</label>
                    <input
                        type="file"
                        name="banner_image"
                        id="banner_image"
                        class="form-control @error('banner_image') is-invalid @enderror"
                        accept="image/*"
                    >
                    @error('banner_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="meta_image" class="form-label">Meta Image</label>
                    <input
                        type="file"
                        name="meta_image"
                        id="meta_image"
                        class="form-control @error('meta_image') is-invalid @enderror"
                        accept="image/*"
                    >
                    @error('meta_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </form>
        </div>
    </div>
@endsection
