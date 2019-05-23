{{-- These are the actual chat messages in their bootstrap / HTML format --}}

@foreach($messages as $message)
    {{-- include the message id in the div for retrieving only new messages --}}
    <div id="msg-{{ $message->id }}" class="mt-2 p-2 rounded-lg" style="background-color:@if($message->trashed()) #fff7f7 @elseif($message->user->id == Auth::id()) #e8f8ff @else #f7fcff @endif">
        {{-- WARNING: DONT REMOVE THIS HTML COMMENT TO THE RIGHT - IT IS USED FOR MESSAGE COUNTING IN chat.js --}}<!--#MSG#-->
        <div class="small text-muted clearfix">
            <span class="float-left" >{{ $message->user->name() }}</span>
            <span class="float-right">
                <span>{{ \App\Tools\Date::toUserOutput($message->created_at) }}</span>
                @if(\App\Tools\PermissionFactory::createDeleteMessage()->has($message->id ))
                    <span class="font-weight-bold" style="cursor:pointer;" onclick="deleteChatMessage({{ $message->id }});" title="{{ __('chat.delete') }}">&nbsp;&times;&nbsp;</span>
                @endif
            </span>
        </div>

        {{-- only show non-deleted messages --}}
        @if($message->trashed())
            <div class="text-muted">{{ __('chat.deleted') }}</div>
        @else
            <div>{{$message->text }}</div>
        @endif
    </div>
@endforeach