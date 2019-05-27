<?php

namespace App\Http\Controllers;

use App\Event;
use App\Group;
use App\Rules\DateTimeValidation;
use App\Tools\Check;
use App\Tools\CustomDateTime;
use App\Tools\Date;
use App\Tools\PermissionFactory;
use App\Tools\Query;
use DemeterChain\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class EventController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // check for permission to create a new event
        PermissionFactory::createCreateEvent()->check();

        // list all current groups for selection and show view
        $groups = Auth::user()->groups()->get();
        return view('event-create')->with('groups', $groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // check for permission to create a new event
        PermissionFactory::createCreateEvent()->check();

        $data = $request->all();
        // never trust any user input
        $this->validateInput($data);

        // Create new event from passed data
        $event = new Event();
        $event = $this->collectData($data, $event, true);
        $event->created_by = Auth::user()->id;

        $event->save();

        // redirect to home calendar
        return redirect('home')->with('newEvent', $data['name']);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // check for permission to show the event
        PermissionFactory::createShowEvent()->check($id);

        // retrieve the corresponding event from database
        $event = Event::findOrFail($id);

        // convert the database timestamps to data and time in the user's timezone
        $start = new CustomDateTime($event->start_time);
        $end = new CustomDateTime($event->end_time);

        // pass all data to the view
        return view('event-show')->with(['event' => $event, 'start' => $start, 'end' => $end]);

        // TODO show details (chat, etc. to members with the appropriate permissions (showEventExtended)
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);

        // retrieve the corresponding event from database
        $event = Event::findOrFail($id);

        // convert the database timestamps to data and time in the user's timezone
        $start = new CustomDateTime($event->start_time);
        $end = new CustomDateTime($event->end_time);

        // pass all data to the view
        return view('event-update')->with(['id' => $id, 'event' => $event, 'start' => $start, 'end' => $end]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // check for permission to edit the event
        PermissionFactory::createEditEvent()->check($id);

        // retrieve the corresponding event from database
        $data = $request->all();
        $event = Event::findOrFail($id);

        // never trust any user input
        $this->validateInput($data);

        // Update database record with form data
        $event = $this->collectData($data, $event);
        $event->save();
        // Redirect to edit page
        return redirect(route('event.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {

        PermissionFactory::createDeleteEvent()->check($id);

        // fetch event from database
        $event = Event::findOrFail($id);

        // delete event from database - this will trigger all dependencies to be removed as well
        $event->delete();

        // redirect to home screen and show alert message
        return redirect('home')->with('EventDeleted', $event->name);
    }

    //  validateInput
    //
    //  This function determines whether the given data for an event is valid.
    //
    private function validateInput(array $data)
    {

        // workaround for array index access later on
        // see null-coalescing operator in PHP manual for details
        $data['start-date'] = $data['start-date'] ?? '';
        $data['end-date']   = $data['end-date']   ?? '';
        $data['start-time'] = $data['start-time'] ?? '';
        $data['end-time']   = $data['end-time']   ?? '';

        // concatenate date and time for later validation
        $data['start-total'] = $data['start-date'] . ' ' . $data['start-time'];
        $data['end-total']   = $data['end-date'] . ' ' . $data['end-time'];

        // validate all input data against the following rules
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
            'location' => 'nullable|string|max:255',
            'start-date' => 'required|date',
        ]);

        // local callback function for the conditional validation
        $notAllDay = function ($input) {
            return !isset($input['all-day-event']);
        };

        // require times and end-date if the event is not an all-day event
        $validator->sometimes('start-time', 'required|date_format:H:i', $notAllDay);
        $validator->sometimes('end-date', 'required|date', $notAllDay);
        $validator->sometimes('end-time', 'required|date_format:H:i', $notAllDay);

        // check for positive event duration -> therefore, use combine dates and times
        $validator->sometimes('end-total', 'required|date_format:Y-m-d H:i|after:start-total', $notAllDay);

        $validator->validate();
    }

    //  collectData
    //
    //  This function takes the form input array $data and copies it into the properties of the
    //  corresponding event.
    //
    private function collectData(array $data, Event $event, $create = false)
    {

        // copy all the standard stuff
        $event->name = $data['name'];
        $event->description = $data['description'];
        $event->location = $data['location'];

        // The group of the event may only be changed during creation of the event!
        if ($create) {
            // if the event shall belong to a group
            if (!empty($data['selectGroup'])) {
                // check for permission to create an event in this group
                PermissionFactory::createCreateEventForGroup()->check($data['selectGroup']);
                // create event for the group
                $event->group_id = $data['selectGroup'];
            } else {
                // private event
                $event->group_id = null;
            }

        }

        // different handling for all-day events
        if (isset($data['all-day-event'])) {
            $event->all_day = true;
            $event->start_time = Date::parseFromInput($data['start-date'], '00:00');
            $event->end_time = Date::parseFromInput($data['start-date'], '23:59');
        } else {
            $event->all_day = false;
            $event->start_time = Date::parseFromInput($data['start-date'], $data['start-time']);
            $event->end_time = Date::parseFromInput($data['end-date'], $data['end-time']);
        }
        return $event;
    }

    public function replies()
    {
        $eventWithoutReply = Query::getNotifications();

        return view('event-replies')->with(['noReply' => $eventWithoutReply]);
    }

    public function updateReplies(Request $request, $eventReplyID)
    {
        $data = $request->all();

        $event = Event::findOrFail($eventReplyID);

        if(isset($data['accept'])) {
            $event->replies()->attach(Auth::user(), ['status' => Event::STATUS_ACCEPTED]);
            return redirect('home')->with(['event' => $event->name, 'newReply' => 'accept']);

        }
        elseif (isset($data['reject'])) {
            $event->replies()->attach(Auth::user(), ['status' => Event::STATUS_REJECTED]);
            return redirect('home')->with(['event' => $event->name, 'newReply' => 'reject']);

        }
        else {
            $event->replies()->attach(Auth::user(), ['status' => Event::STATUS_TENTATIVE]);
            return redirect('home')->with(['event' => $event->name, 'newReply' => 'tentative']);

        }
    }
}
