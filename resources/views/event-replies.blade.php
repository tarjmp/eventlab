@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('event.event_replies_title') }}</h2>

                @forelse($tentative as $t)

                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-md-8">
                                <p>{{ __('event.notifications_text', ['name' => $t->name]) }}</p>
                                <a class="nav" href="{{ route('participants')}}">
                                    {{ __('event.details_text') }}
                                </a>
                            </div>
                            <div class="col-md-4">
                                <form method="POST"
                                      action="{{ route('notificationsUpdate', ['status' => 'rejected', 'event' => $t]) }}">
                                    @csrf

                                    <button id="btn_rejectEvent" type="submit"
                                            class="btn btn-outline-danger float-right ml-1">
                                        {{ __('event.notifications_reject') }}
                                    </button>

                                </form>

                                <form method="POST"
                                      action="{{ route('notificationsUpdate', ['status' => 'accepted', 'event' => $t]) }}">
                                    @csrf

                                    <button id="btn_acceptEvent" type="submit"
                                            class="btn btn-outline-success float-right">
                                        {{ __('event.notifications_accept') }}
                                    </button>
                                </form>
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
