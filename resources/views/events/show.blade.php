@extends('layouts.app')

@section('title', $event->title)

@section('meta')
    @if ($event->meta_image)
    <meta property="og:image" content="{{ $event->meta_image }}">
    <meta property="og:title" content="{{ $event->title }}">
    <meta property="og:description" content="{{ $event->description }}">
    <meta name="twitter:card" content="summary_large_image">
    @endif
@endsection

@section('banner')
    @if ($event->banner_image)
        <div class="mb-4">
            <img src="{{ $event->banner_image }}" alt="Event Banner"
                class="img-fluid w-100 shadow-sm" style="max-height: 320px; object-fit: cover;">
        </div>
    @endif
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h3 class="card-title">{{ $event->title }}</h3>
            <p class="text-muted">{{ $event->description }}</p>
        </div>
        @auth
            @if ($event->user_id === auth()->id())
                <div class="px-2 mb-3">
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary">Edit</a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger">Delete Event</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>

    @if (session('rsvp_id'))
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="POST" action="{{ route('events.rsvp', $event) }}">
                    @csrf
                    <input type="hidden" name="phone" value="{{ \App\Models\Rsvp::find(session('rsvp_id'))->phone }}">
                    <div class="mb-3">
                        <label for="item" class="form-label">What are you bringing?</label>
                        <input type="text" id="item" name="item" class="form-control @error('item') is-invalid @enderror" required>
                        @error('item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success w-100">Add Item</button>
                </form>
            </div>
        </div>
    @endif

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h4 class="card-title">Who's Bringing What</h4>
        <ul class="list-group list-group-flush mt-3">
            @php $shown = false; @endphp
            @foreach ($event->rsvps as $rsvp)
                @if ($rsvp->items->isNotEmpty())
                    @php $shown = true; @endphp
                    <li class="list-group-item">
                        <strong>{{ $rsvp->name }}</strong>
                        <ul class="mt-2">
                            @foreach ($rsvp->items as $item)
                                <li class="d-flex justify-content-between align-items-center">
                                    <span>{{ $item->item }}</span>
                                    @if (session('rsvp_id') === $rsvp->id)
                                        <form action="{{ route('rsvp.item.destroy', [$event, $item]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            @unless ($shown)
                <li class="list-group-item text-muted">No one has RSVP’d with an item yet.</li>
            @endunless
        </ul>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
            <label class="form-label">Share this event</label>
            <div class="input-group mb-3">
                <input type="text" id="shareLink" class="form-control" readonly value="{{ route('events.show', $event) }}">
                <button id="copyLinkBtn" class="btn btn-outline-secondary" type="button">Copy</button>
            </div>
            <small id="copiedNotice" class="form-text text-success" style="display: none;">Link copied!</small>
    </div>
</div>
    @if (!session('rsvp_id'))
        <!-- Phone Modal -->
        <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="verifyPhoneForm" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Enter Your Phone Number</h5>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" id="verifyPhone" placeholder="Phone Number" required>
                        <div id="verifyError" class="text-danger mt-2 d-none">Phone not found. Please enter your name next.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Continue</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Name Modal -->
        <div class="modal fade" id="nameModal" tabindex="-1" aria-labelledby="nameModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('events.rsvp', $event) }}" class="modal-content">
                    @csrf
                    <input type="hidden" name="phone" id="hiddenPhone">
                    <div class="modal-header">
                        <h5 class="modal-title">What's Your Name?</h5>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control mb-2 @error('name') is-invalid @enderror" name="name" placeholder="Your Name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @error('phone')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success w-100">Submit RSVP</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        $(function () {
            @if (!session('rsvp_id'))
                const phoneModal = new bootstrap.Modal(document.getElementById('phoneModal'));
                const nameModal = new bootstrap.Modal(document.getElementById('nameModal'));
                phoneModal.show();

                $('#verifyPhoneForm').on('submit', function (e) {
                    e.preventDefault();
                    const phone = $('#verifyPhone').val();

                    $.ajax({
                        url: '{{ route('rsvp.verify', $event) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            phone: phone,
                        },
                        success: function (res) {
                            if (res.found) {
                                location.reload();
                            } else {
                                $('#hiddenPhone').val(phone);
                                phoneModal.hide();
                                nameModal.show();
                            }
                        }
                    });
                });
            @endif

            $('#copyLinkBtn').on('click', function () {
                const $input = $('#shareLink');
                $input.select();
                document.execCommand('copy');
                $('#copiedNotice').fadeIn(200).delay(2000).fadeOut(400);
            });
        });
    </script>
@endsection
