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
            <div class="col-md-8">
                <h2>{{ __('event.event_replies_title') }}</h2>

                @forelse($noReply as $n)

                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-md-8">
                                <p>{{ __('event.notifications_text', ['name' => $n->name]) }}</p>
                                <a class="nav" href="{{ route('event.show', $n->id)}}">
                                    {{ __('event.details_text') }}
                                </a>
                            </div>
                            <div class="col-md-4">
                                <div class="container">
                                    <form method="POST"
                                          action="{{ route('notificationsUpdate', ['event' => $n]) }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-6 p-0 pr-1">
                                                <input id="btn_acceptEvent" type="submit" name="accept"
                                                       value="{{ __('event.notifications_accept') }}"
                                                       class="btn btn-outline-success w-100"/>
                                            </div>
                                            <div class="col-6 p-0 pl-1">
                                                <input id="btn_rejectEvent" type="submit" name="reject"
                                                       value="{{ __('event.notifications_reject') }}"
                                                       class="btn btn-outline-danger w-100"/>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12 p-0">
                                                <input id="btn_tentativeEvent" type="submit" name="tentative"
                                                       value="{{ __('event.notifications_tentative') }}"
                                                       class="btn btn-outline-secondary w-100"/>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{ __('event.no_notifications') }}
                @endforelse
            </div>
        </div>
    </div>
@endsection
