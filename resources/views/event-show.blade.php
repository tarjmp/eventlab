@extends('layouts.app')

@section('content')
    <div class="container">

        @if (session('newReply'))
            <div class="alert alert-info" role="alert">
                @if(session('newReply') == \App\Event::STATUS_ACCEPTED)
                    {{ __('event.replied_accepted', ['name' => session('event')]) }}
                @elseif(session('newReply') == \App\Event::STATUS_REJECTED)
                    {{ __('event.replied_rejected', ['name' => session('event')]) }}
                @else
                    {{ __('event.replied_tentative', ['name' => session('event')]) }}
                @endif
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                <div class="row">
                    @auth
                        <div class="col-12"><h2>{{$event->name}}</h2><br></div>
                    @else
                        <div class="col-6"><h2>{{$event->name}}</h2><br></div>@endauth

                    @if(\App\Tools\PermissionFactory::createShowEvent()->has($event->id) && !\App\Tools\Check::isMyPrivateEvent($event->id))
                        <div class="col-6">
                            @if($event->hasEventReply())
                                @if($event->myReply() == \App\Event::STATUS_ACCEPTED)
                                    <span class="badge-lg badge-success badge-pill" style="float: right;"
                                          id="my-reply">{{ \App\Event::STATUS_ACCEPTED }}</span><br><br>
                                @elseif($event->myReply() == \App\Event::STATUS_REJECTED)
                                    <span class="badge-lg badge-danger badge-pill" style="float: right;"
                                          id="my-reply">{{ \App\Event::STATUS_REJECTED}}</span><br><br>
                                @else
                                    <span class="badge-lg badge-secondary badge-pill" style="float: right;"
                                          id="my-reply">{{ \App\Event::STATUS_TENTATIVE }}</span><br><br>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>


                @if($event->description != '')
                    <p class="text-muted">{{$event->description}}</p><br>
                @endif

                @if($event->group)
                    <div class="row mb-4">
                        <div class="col-md-4">{{ __('event.group') }}:</div>
                        <div class="col-md-8 text-right"><a
                                    href="{{ route('group.show', $event->group->id) }}">{{ $event->group->name }}</a>
                        </div>
                    </div>
                @else
                    <div class="row mb-4">
                        <div class="col-12 text-muted">{{ __('event.private') }}</div>
                    </div>
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
                    <div class="row mb-4">
                        <div class="col-md-8">{{ __('event.members_status') }}:</div>
                    </div>
                    <div class="col-md-12">
                        @if(count($event->membersReply()->get()) > 0)
                            <div class="row mb-4">
                                @foreach($event->membersAccepted() as $r)
                                    <div class="col-md-4">
                                        {{ $r->name() }}
                                    </div>
                                    <div class="col-md-8 text-right">
                                            <span class="badge badge-success badge-pill"
                                                  id="my-reply">{{__('event.status_accepted')}}</span>
                                    </div>
                                @endforeach
                                @foreach($event->membersRejected() as $r)
                                    <div class="col-md-4">
                                        {{  $r->name()  }}
                                    </div>
                                    <div class="col-md-8 text-right">
                                            <span class="badge badge-danger badge-pill"
                                                  id="my-reply">{{ __('event.status_rejected') }}</span>
                                    </div>
                                @endforeach
                                @foreach($event->membersTentative() as $r)
                                    <div class="col-md-4">
                                        {{  $r->name()  }}</div>
                                    <div class="col-md-8 text-right">
                                            <span class="badge badge-secondary badge-pill"
                                                  id="my-reply">{{ __('event.status_tentative') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="row mb-4">
                            @foreach($event->notRepliedMembers() as $m)
                                <div class="col-md-4">
                                    {{  $m->name()  }}</div>
                                <div class="col-md-8 text-right">
                                    {{ __('event.not_replied') }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <br>
                @if(\App\Tools\PermissionFactory::createShowEvent()->has($event->id) && !\App\Tools\Check::isMyPrivateEvent($event->id))
                    @auth
                        <div class="container">
                            <form method="POST"
                                  action="{{ route('notificationsUpdate', ['event' => $event]) }}">
                                @csrf
                                <div class="row">
                                    @if($event->myReply() != \App\Event::STATUS_ACCEPTED)
                                        <div class="p-0 pr-1">
                                            <input id="btn_acceptEvent" type="submit" name="accept"
                                                   value="{{ __('event.notifications_accept') }}"
                                                   class="btn btn-outline-success w-100"/>
                                        </div>
                                    @endif
                                    @if($event->myReply() != \App\Event::STATUS_TENTATIVE)
                                        <div class="p-0 pr-1">
                                            <input id="btn_tentativeEvent" type="submit" name="tentative"
                                                   value="{{ __('event.notifications_tentative') }}"
                                                   class="btn btn-outline-secondary w-100"/>
                                        </div>
                                    @endif
                                    @if($event->myReply() != \App\Event::STATUS_REJECTED)
                                        <div class="p-0">
                                            <input id="btn_rejectEvent" type="submit" name="reject"
                                                   value="{{ __('event.notifications_reject') }}"
                                                   class="btn btn-outline-danger w-100"/>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endauth
                @endif

                @if(\App\Tools\PermissionFactory::createEditEvent()->has($event->id))
                    <br>
                    <a id="editEvent" class="btn btn-primary" href="{{ route('event.edit', $event->id) }}">
                        {{ __('event.edit') }}
                    </a>
                @endif
                @if(\App\Tools\PermissionFactory::createShowEventExtended()->has($event->id))
                    <a id="showList" class="btn btn-primary" href="{{ route('list', $event->id) }}">
                        {{ __('list.show') }}
                    </a>
                @endif
            </div>

            {{-- Include chat window --}}
            @if(\App\Tools\PermissionFactory::createShowEventExtended()->has($event->id))
                @include('chat.window', ['event' => $event, 'private' => $private])
            @endif

        </div>
    </div>

    <script type="text/javascript" src="{{ asset('js/chat.js') }}"></script>
@endsection
