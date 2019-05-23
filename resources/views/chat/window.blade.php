{{-- This is the chat window - the actual chat messages are included in the messages.blade template --}}


<div class="col-lg-6 mx-2 mx-lg-0 pl-lg-4">
    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            @if($private) {{ __('chat.title_private') }} @else {{ __('chat.title') }} @endif
            <span class="badge badge-primary badge-pill" id="msg-count">{{ $event->messages()->count() }}</span>
        </li>
        <li class="list-group-item" id="msg-scroll" style="max-height: 500px; overflow-y: auto;">
            <div id="messages" class="my-2">
                {{-- include chat messages here --}}
                @if($event->messages()->count() > 0)
                    @include('chat.messages', ['messages' => $event->messages, 'private' => $private])
                @else
                    {{-- no chat messages - show default text --}}
                    <div class="mt-2 p-2 rounded-lg" style="background-color:#f3f3f3" id="msg-0">
                        <div class="mt-1 text-muted">No messages yet... Start typing!</div>
                    </div>
                @endif
            </div>
            <a id="msg-bottom"></a>
        </li>
        <li class="list-group-item">
            <form id="msg-form" class="input-group input-group-sm mb-3" onsubmit="return addChatMessage();" action="{{ route('add-message') }}">
                @csrf
                <input type="hidden" name="event" value="{{ $event->id }}"/>
                <input type="hidden" name="msg-id" value="0"/>
                <input type="text" name="message" id="message" class="form-control" placeholder="{{ __('chat.enter_message') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit" id="msg-submit">{{ __('chat.send') }}</button>
                </div>
            </form>
            <form id="msg-delete" method="POST" action="{{ route('delete-message') }}">
                @csrf
                <input type="hidden" name="id" value="0"/>
            </form>
            <form id="msg-refresh" method="POST" action="{{ route('get-messages') }}">
                @csrf
                <input type="hidden" name="event" value="{{ $event->id }}"/>
                <input type="hidden" name="msg-id" value="0"/>
            </form>
        </li>
    </ul>
</div>