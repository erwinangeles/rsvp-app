<!DOCTYPE html>
<html>
<head>
    <title>{{ $event->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-4">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h3 class="card-title">{{ $event->title }}</h3>
                <p class="text-muted">{{ $event->description }}</p>
                <div class="mb-4">
                    <label class="form-label">Share this event</label>
                    <div class="input-group">
                        <input type="text" id="shareLink" class="form-control" readonly value="{{ route('events.show', $event) }}">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">Copy</button>
                    </div>
                    <small id="copiedNotice" class="form-text text-success mt-1" style="display: none;">Link copied!</small>
                </div>
            </div>
        </div>
        

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title">RSVP</h4>
                <form method="POST" action="{{ route('events.rsvp', $event) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">What are you bringing?</label>
                        <input name="item" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Who's Bringing What</h4>
                <ul class="list-group list-group-flush mt-3">
                    @forelse ($event->rsvps as $rsvp)
                        <li class="list-group-item">{{ $rsvp->name }} is bringing <strong>{{ $rsvp->item }}</strong></li>
                    @empty
                        <li class="list-group-item text-muted">No one has RSVP’d yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <script>
        function copyLink() {
            const input = document.getElementById("shareLink");
            input.select();
            input.setSelectionRange(0, 99999); // For mobile

            navigator.clipboard.writeText(input.value).then(() => {
                document.getElementById("copiedNotice").style.display = "block";
                setTimeout(() => {
                    document.getElementById("copiedNotice").style.display = "none";
                }, 2000);
            });
        }
    </script>
</body>
</html>