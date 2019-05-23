{{-- These are the actual chat messages in their bootstrap / HTML format --}}

@foreach($messages as $message)
    {{-- include the message id in the div for retrieving only new messages --}}
    <div id="msg-{{ $message->id }}" class="mt-2 p-2 rounded-lg msg-default @if($message->trashed()) msg-trashed @elseif($message->user->id == Auth::id()) msg-own @endif">
        {{-- WARNING: DONT REMOVE THIS HTML COMMENT TO THE RIGHT - IT IS USED FOR MESSAGE COUNTING IN chat.js --}}<!--#MSG#-->
        <div class="small text-muted clearfix msg-top">
            @if($private)
                <span class="float-left msg-date">{{ \App\Tools\Date::toUserOutput($message->created_at) }}</span>
                <span class="float-right">
                    @if(\App\Tools\PermissionFactory::createDeleteMessage()->has($message->id ))
                        <span class="font-weight-bold" style="cursor:pointer;" onclick="deleteChatMessage({{ $message->id }});" title="{{ __('chat.delete') }}">&nbsp;&times;&nbsp;</span>
                    @endif
                </span>
            @else
                <span class="float-left">{{ $message->user->name() }}</span>

                <span class="float-right">
                    <span class="msg-date">{{ \App\Tools\Date::toUserOutput($message->created_at) }}</span>
                    @if(\App\Tools\PermissionFactory::createDeleteMessage()->has($message->id ))
                        <span class="font-weight-bold" style="cursor:pointer;" onclick="deleteChatMessage({{ $message->id }});" title="{{ __('chat.delete') }}">&nbsp;&times;&nbsp;</span>
                    @endif
                </span>
            @endif
        </div>

        {{-- only show non-deleted messages --}}
        @if($message->trashed())
            <div class="text-muted">{{ __('chat.deleted') }}</div>
        @else
            <div>{{$message->text }}</div>
        @endif
    </div>
@endforeach