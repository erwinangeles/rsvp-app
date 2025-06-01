<!DOCTYPE html>
<html>
<head>
    <title>{{ $event->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light py-4">
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="card-title">{{ $event->title }}</h3>
            <p class="text-muted">{{ $event->description }}</p>

            <label class="form-label">Share this event</label>
            <div class="input-group mb-3">
                <input type="text" id="shareLink" class="form-control" readonly value="{{ route('events.show', $event) }}">
                <button id="copyLinkBtn" class="btn btn-outline-secondary" type="button">Copy</button>
            </div>
            <small id="copiedNotice" class="form-text text-success" style="display: none;">Link copied!</small>
        </div>
    </div>

    @if (session('rsvp_id'))
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title">RSVP</h4>
                <form method="POST" action="{{ route('events.rsvp', $event) }}">
                    @csrf
                    <input name="item" class="form-control mb-2" placeholder="What are you bringing?" required>
                    <input type="hidden" name="phone" value="{{ \App\Models\Rsvp::find(session('rsvp_id'))->phone }}">
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Who's Bringing What</h4>
            <ul class="list-group list-group-flush mt-3">
                @forelse ($event->rsvps as $rsvp)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $rsvp->name }} is bringing <strong>{{ $rsvp->item }}</strong>
                        </div>
                        @if (session('rsvp_id') === $rsvp->id)
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="modal"
                                   data-bs-target="#editRsvpModal"
                                   data-url="{{ route('rsvp.update', [$event, $rsvp]) }}"
                                   data-name="{{ $rsvp->name }}"
                                   data-item="{{ $rsvp->item }}"
                                   data-phone="{{ $rsvp->phone }}">
                                    Edit
                                </a>
                                <form action="{{ route('rsvp.destroy', [$event, $rsvp]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-muted">No one has RSVP’d yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editRsvpModal" tabindex="-1" aria-labelledby="editRsvpLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editRsvpForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRsvpLabel">Edit Your RSVP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" id="editRsvpName" class="form-control mb-2" required>
                    <input type="text" name="item" id="editRsvpItem" class="form-control mb-2" required>
                    <input type="text" name="phone" id="editRsvpPhone" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- First Time User Modals --}}
@if (!session('rsvp_id'))
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

    <div class="modal fade" id="nameModal" tabindex="-1" aria-labelledby="nameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('events.rsvp', $event) }}" class="modal-content">
                @csrf
                <input type="hidden" name="phone" id="hiddenPhone">
                <div class="modal-header">
                    <h5 class="modal-title">What's Your Name?</h5>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" name="name" placeholder="Your Name" required>
                    <input type="text" class="form-control mb-2" name="item" placeholder="What are you bringing?" required>
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

    $('#editRsvpModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const modal = $(this);

        modal.find('#editRsvpForm').attr('action', button.data('url'));
        modal.find('#editRsvpName').val(button.data('name'));
        modal.find('#editRsvpItem').val(button.data('item'));
        modal.find('#editRsvpPhone').val(button.data('phone'));
    });
});
</script>
</body>
</html>