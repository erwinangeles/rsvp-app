@extends('layouts.app')

@section('title', $event->title)

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="card-title">{{ $event->title }}</h3>
            <p class="text-muted">{{ $event->description }}</p>
        </div>
    </div>

    @if (session('rsvp_id'))
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title">Add Item</h4>

                <form method="POST" action="{{ route('events.rsvp', $event) }}">
                    @csrf
                    <input type="hidden" name="phone" value="{{ \App\Models\Rsvp::find(session('rsvp_id'))->phone }}">
                    <div class="mb-3">
                        <label for="item" class="form-label">What are you bringing?</label>
                        <input placeholder="ex: Mac and Cheese 🧀" type="text" id="item" name="item" class="form-control @error('item') is-invalid @enderror" required>
                        @error('item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">Who's Bringing What</h4>
            <ul class="list-group list-group-flush mt-3">
                @forelse ($event->rsvps as $rsvp)
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
                @empty
                    <li class="list-group-item text-muted">No one has RSVP’d yet.</li>
                @endforelse
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
                        <input type="text" class="form-control" id="verifyPhone" name="phone" placeholder="Phone Number" required>
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

                        <input type="text" class="form-control mb-2 @error('item') is-invalid @enderror" name="item" placeholder="What are you bringing?" required>
                        @error('item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <input type="hidden" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
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
                                phoneModal.hide();
                                $('#hiddenPhone').val(phone);
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