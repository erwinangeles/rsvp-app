<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', auth()->id())->latest()->get();
        return view('welcome', compact('events'));
    }
}
