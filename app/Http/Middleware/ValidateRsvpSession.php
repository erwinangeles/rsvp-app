<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Rsvp;

class ValidateRsvpSession
{
    public function handle(Request $request, Closure $next)
    {
        $rsvpId = session('rsvp_id');

        if ($rsvpId) {
            $rsvp = Rsvp::find($rsvpId);

            // If RSVP doesn't exist or doesn't belong to the current event
            if (
                !$rsvp ||
                !$request->route('event') || // event missing
                $rsvp->event_id !== $request->route('event')->id // mismatched
            ) {
                session()->forget('rsvp_id');
            }
        }

        return $next($request);
    }
}