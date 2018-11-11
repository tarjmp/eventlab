<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateEventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('create-event');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:2048',
            'date'          => 'date',
        ]);

        $event = new Event();
        $event->name       = $data['name'];
        $event->description = $data['description'];
        $event->date        = $data['date'];
        $event->save();

        return redirect('home')->with('newEvent', $data['name']);
    }
}
