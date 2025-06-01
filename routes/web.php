<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index'])->name('home');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
Route::post('/events/{event}/rsvp/verify', [EventController::class, 'verifyPhone'])->name('rsvp.verify');
Route::get('/events/{event}/rsvp/{rsvp}/edit', [EventController::class, 'edit'])->name('rsvp.edit');
Route::put('/events/{event}/rsvp/{rsvp}', [EventController::class, 'update'])->name('rsvp.update');
Route::delete('/events/{event}/rsvp/{rsvp}', [EventController::class, 'destroy'])->name('rsvp.destroy');