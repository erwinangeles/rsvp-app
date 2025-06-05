@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="container py-4">
                <h2 class="mb-4">Edit Event</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $event->title) }}"
                            class="form-control @error('title') is-invalid @enderror"
                            required
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description (optional)</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                     <div class="mb-3">
                        <label for="banner_image" class="form-label">Banner Image (optional)</label>
                        @if ($event->banner_image)
                            <div class="mb-2">
                                <img src="{{ $event->banner_image }}" alt="Current Meta Image" class="img-fluid rounded border" style="max-height: 200px;">
                            </div>
                        @endif
                        <input
                            type="file"
                            name="banner_image"
                            id="banner_image"
                            class="form-control @error('banner_image') is-invalid @enderror"
                        >
                        @error('banner_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_image" class="form-label">Meta Image (optional)</label>
                        @if ($event->meta_image)
                            <div class="mb-2">
                                <img src="{{ $event->meta_image }}" alt="Current Meta Image" class="img-fluid rounded border" style="max-height: 200px;">
                            </div>
                        @endif
                        <input
                            type="file"
                            name="meta_image"
                            id="meta_image"
                            class="form-control @error('meta_image') is-invalid @enderror"
                        >
                        @error('meta_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('home')}}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection