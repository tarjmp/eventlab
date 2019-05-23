<?php

namespace App\Http\Controllers;

use App\Event;
use App\Message;
use App\Tools\PermissionFactory;
use App\Tools\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function add(Request $oRequest) {

        // check for a valid event id and text message
        $oRequest->validate([
            'event'   => 'required|integer',
            'message' => 'required|string|max:255',

        ]);

        // retrieve form data into array
        $aData = $oRequest->all();

        $iEventId = intval($aData['event']);

        // now, really validate the event id by checking for the required permissions
        PermissionFactory::createShowEventExtended()->check($iEventId);

        // everything looks good, insert message into database
        $oMessage = new Message();
        $oMessage->event_id = $iEventId;
        $oMessage->text     = $aData['message'];
        $oMessage->user_id  = Auth::id();

        $oMessage->save();

        return view('chat.messages', ['messages' => Query::getNewMessagesForEvent($iEventId, $oMessage->id)]);

    }

    public function delete(Request $oRequest) {

        // check for a valid event id and text message
        $oRequest->validate([
            'id'   => 'required|integer',
        ]);

        // retrieve form data into array
        $aData = $oRequest->all();
        $iMessageID = intval($aData['id']);

        // check if the user has permission to delete the requested message
        PermissionFactory::createDeleteMessage()->check($iMessageID);

        // everything looks good, simply override message text (do not delete message from database)
        $oMessage = Message::findOrFail($iMessageID);
        $oMessage->text = __('chat.deleted');
        $oMessage->save();

        return redirect(route('event.show', $oMessage->event->id));

    }
}
