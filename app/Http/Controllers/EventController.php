<?php

namespace App\Http\Controllers;

use App\Event;
use App\Rules\DateTimeValidation;
use App\Tools\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // require valid user login
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('event-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
            'location' => 'nullable|string|max:255',
            'start-date' => 'required|date',
        ]);

        $notAllDay = function ($input) {
            return !isset($input['all-day-event']);
        };

        // require times if no all-day event
        $validator->sometimes('start-time', new DateTimeValidation($data['start-date']), $notAllDay);
        $validator->sometimes('end-date', 'required|date', $notAllDay);
        $validator->sometimes('end-time', new DateTimeValidation($data['end-date']), $notAllDay);

        if ($validator->fails()) {
            // invalid input
            return redirect('event/create')->withErrors($validator->errors())->withInput();

        } else {
            $event = new Event();
            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->location = $data['location'];
            $event->all_day = false;
            $event->group_id = null;
            $event->created_by = Auth::user()->id;

            // TODO: Also support all-day events
            // -> the time then needs to be 00:00-23:59 in the user's time zone, right?
            // this should be discussed in our team

            $event->start_time = Date::parseFromInput($data['start-date'], $data['start-time']);
            $event->end_time = Date::parseFromInput($data['end-date'], $data['end-time']);
            $event->save();

            return redirect('home')->with('newEvent', $data['name']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);
        $start_date = Date::toUserOutput($event->start_time, 'Y-m-d');
        $start_time = Date::toUserOutput($event->start_time, 'H:i');
        $end_date = Date::toUserOutput($event->end_time, 'Y-m-d');
        $end_time = Date::toUserOutput($event->end_time, 'H:i');
        return view('event-show')->with(['event' => $event, 'start_date' => $start_date, 'start_time' => $start_time, 'end_date' => $end_date, 'end_time' => $end_time]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        $start_date = Date::toUserOutput($event->start_time, 'Y-m-d');
        $start_time = Date::toUserOutput($event->start_time, 'H:i');
        $end_date = Date::toUserOutput($event->end_time, 'Y-m-d');
        $end_time = Date::toUserOutput($event->end_time, 'H:i');
        return view('event-update')->with(['id' => $id, 'event' => $event, 'start_date' => $start_date, 'start_time' => $start_time, 'end_date' => $end_date, 'end_time' => $end_time]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $event = Event::find($id);
        //TODO verification
        $event->save();
        return view('event-update')->with('updated', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
