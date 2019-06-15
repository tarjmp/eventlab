<?php

namespace App\Http\Controllers;

use App\Event;
use App\Message;
use App\Tools\Check;
use App\Tools\PermissionFactory;
use App\Tools\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Add a new chat message
    public function add(Request $oRequest) {

        // check for a valid event id and text message
        $oRequest->validate([
            'event'   => 'required|integer',
            'message' => 'required|string|max:255',
            'msg-id'  => 'required|integer',

        ]);

        // retrieve form data into array
        $aData = $oRequest->all();

        $iEventId = intval($aData['event']);
        $iMsgId   = intval($aData['msg-id']);

        // now, really validate the event id by checking for the required permissions
        PermissionFactory::createShowEventExtended()->check($iEventId);

        // everything looks good, insert message into database
        $oMessage = new Message();
        $oMessage->event_id = $iEventId;
        $oMessage->text     = $aData['message'];
        $oMessage->user_id  = Auth::id();

        $oMessage->save();

        return view('chat.messages', ['messages' => Query::getNewMessagesForEvent($iEventId, $iMsgId + 1), 'private' => Check::isMyPrivateEvent($iEventId)]);

    }

    // Retrieve all new chat messages - the given message id is excluded from the return values
    public function get(Request $oRequest) {

        // check for a valid event id and text message
        $oRequest->validate([
            'event'   => 'required|integer',
            'msg-id'  => 'required|integer',

        ]);

        // retrieve form data into array
        $aData = $oRequest->all();
        $iEventId = intval($aData['event']);
        $iMsgId   = intval($aData['msg-id']);

        // validate the event id by checking for the required permissions
        PermissionFactory::createShowEventExtended()->check($iEventId);

        // everything looks good, retrieve all new messages
        return view('chat.messages', ['messages' => Query::getNewMessagesForEvent($iEventId, $iMsgId + 1), 'private' => Check::isMyPrivateEvent($iEventId)]);

    }

    // Delete a chat message
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

        // everything looks good, simply soft-delete message (do not delete message from database)
        $oMessage = Message::findOrFail($iMessageID);
        $oMessage->delete();

        return redirect(route('event.show', $oMessage->event->id));

    }
}
