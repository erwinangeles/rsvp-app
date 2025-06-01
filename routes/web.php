<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RsvpItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('events.create'));

Route::get('/events/create', [EventController::class, 'index'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/verify', [EventController::class, 'verifyPhone'])->name('rsvp.verify');
Route::post('/events/{event}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');

Route::delete('/events/{event}/item/{item}', [RsvpItemController::class, 'destroy'])->name('rsvp.item.destroy');