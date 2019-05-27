@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 mb-5 mx-2 mx-lg-0 pr-lg-4">

                @isset($updated)
                    <div class="alert alert-success" role="alert">
                        {{ __('event.updated', ['name' => $event->name]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endisset

                <h2>{{$event->name}}</h2><br>
                @if($event->description != '')
                    <p class="text-muted">{{$event->description}}</p><br>
                @endif

                @if($event->location != '')
                    <div class="row mb-4">
                        <div class="col-md-4">{{ __('event.location') }}:</div>
                        <div class="col-md-8 text-right">{{ $event->location }}</div>
                    </div>
                @endif
                @if($event->all_day)
                    <div class="row mb-4">
                        <div class="col-md-4">{{ __('event.date') }}:</div>
                        <div class="col-md-8 text-right">{{ \App\Tools\Date::toUserOutput($event->start_time, 'M j Y') . ', '. __('event.all_day_small') }}</div>
                    </div>
                @else
                    <div class="row mb-4">
                        <div class="col-md-4">{{ __('event.start_time') }}:</div>
                        <div class="col-md-8 text-right">{{ \App\Tools\Date::toUserOutput($event->start_time, 'M j Y, H:i') }}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">{{ __('event.end_time') }}:</div>
                        <div class="col-md-8 text-right">{{ \App\Tools\Date::toUserOutput($event->end_time, 'M j Y, H:i') }}</div>
                    </div>
                @endif

                @if(\App\Tools\PermissionFactory::createShowEventExtended()->has($event->id) && !\App\Tools\Check::isMyPrivateEvent($event->id))
                    @if(\App\Tools\Query::getHasEventReply($event->id))
                        <br>
                        <div class="row mb-4">
                            <div class="col-md-4">{{ __('event.status') }}:</div>
                            <div class="col-md-8 text-right">{{ \App\Tools\Query::getStatusEvent($event->id) }}</div>
                        </div>

                    @else
                        <br>
                        <div class="container">
                            <form method="POST"
                                  action="{{ route('notificationsUpdate', ['event' => $event]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-4 p-0 pr-1">
                                        <input id="btn_acceptEvent" type="submit" name="accept"
                                               value="{{ __('event.notifications_accept') }}"
                                               class="btn btn-outline-success w-100"/>
                                    </div>
                                    <div class="col-4 p-0 pr-1">
                                        <input id="btn_rejectEvent" type="submit" name="reject"
                                               value="{{ __('event.notifications_reject') }}"
                                               class="btn btn-outline-danger w-100"/>
                                    </div>
                                    <div class="col-4 p-0">
                                        <input id="btn_tentativeEvent" type="submit" name="tentative"
                                               value="{{ __('event.notifications_tentative') }}"
                                               class="btn btn-outline-secondary w-100"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif

                @if(\App\Tools\PermissionFactory::createShowEvent()->has($event->id))
                    <br>
                    <a id="editEvent" class="btn btn-primary" href="{{ route('event.edit', $event->id) }}">
                        {{ __('event.edit') }}
                    </a>
                @endif
            </div>

            {{-- Include chat window --}}
            @if(\App\Tools\PermissionFactory::createShowEventExtended()->has($event->id))
                @include('chat.window', ['event' => $event, 'private' => $private])
            @endif

            <br>
            <a id="showList" class="btn btn-primary" href="{{ route('list', $event->id) }}">
                {{ __('list.show') }}
            </a>

        </div>
    </div>

    <script type="text/javascript" src="{{ asset('js/chat.js') }}"></script>
@endsection
