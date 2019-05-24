@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('event.event_replies_title') }}</h2>

                @forelse($noReply as $n)

                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-md-8">
                                <p>{{ __('event.notifications_text', ['name' => $n->name]) }}</p>
                                <a class="nav" href="{{ route('participants')}}">
                                    {{ __('event.details_text') }}
                                </a>
                            </div>
                            <div class="col-md-4">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-6 p-0 pr-1">
                                            <form method="POST"
                                                  action="{{ route('notificationsUpdate', ['status' => 'accepted', 'event' => $n]) }}">
                                                @csrf

                                                <button id="btn_acceptEvent" type="submit"
                                                        class="btn btn-outline-success w-100">
                                                    {{ __('event.notifications_accept') }}
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-6 p-0 pl-1">
                                            <form method="POST"
                                                  action="{{ route('notificationsUpdate', ['status' => 'rejected', 'event' => $n]) }}">
                                                @csrf

                                                <button id="btn_rejectEvent" type="submit"
                                                        class="btn btn-outline-danger w-100">
                                                    {{ __('event.notifications_reject') }}
                                                </button>

                                            </form>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12 p-0">
                                            <form method="POST"
                                                  action="{{ route('notificationsUpdate', ['status' => 'tentative', 'event' => $n]) }}">
                                                @csrf

                                                <button id="btn_tentativeEvent" type="submit"
                                                        class="btn btn-outline-secondary w-100">
                                                    {{ __('event.notifications_tentative') }}
                                                </button>

                                            </form>
                                        </div>
                                    </div>
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
