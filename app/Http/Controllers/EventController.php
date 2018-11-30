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

        $validator = $this->validateInput($data);

        if ($validator->fails()) {
            // invalid input
            return redirect('event/create')->withErrors($validator->errors())->withInput();

        } else {
            $event = new Event();
            $event = $this->collectData($data, $event);
            $event->created_by = Auth::user()->id;

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
        $event = Event::findOrFail($id);
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
        $event = Event::findOrFail($id);
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
        $event = Event::findOrFail($id);

        $validator = $this->validateInput($data);

        if ($validator->fails()) {
            // invalid input
            return redirect('event/edit')->withErrors($validator->errors())->withInput();
        } else {
            $event = $this->collectData($data, $event);

            $event->save();

            return redirect('event/' . $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect('home')->with('EventDeleted', $event->name);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validateInput(array $data)
    {
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
        return $validator;
    }

    /**
     * @param array $data
     * @param Event $event
     * @return mixed
     */
    private function collectData(array $data, Event $event)
    {
        $event->name = $data['name'];
        $event->description = $data['description'];
        $event->location = $data['location'];
        if (isset($data['all-day-event'])) {
            $event->all_day = true;
            $event->start_time = Date::parseFromInput($data['start-date'], '00:00');
            $event->end_time = Date::parseFromInput($data['start-date'], '23:59');
        } else {
            $event->all_day = false;
            $event->start_time = Date::parseFromInput($data['start-date'], $data['start-time']);
            $event->end_time = Date::parseFromInput($data['end-date'], $data['end-time']);
        }
        //TODO: Also implement groups
        $event->group_id = null;
        return $event;
    }
}
