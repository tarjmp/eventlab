@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('event.event_replies_title') }}</h2>

                <div class="alert alert-info" role="alert">
                    <div class="row">
                        <div class="col-md-8">
                            <p>{{ __('event.notifications_text') }}</p>
                            <a class="nav" href="{{ route('participants')}}">
                                {{ __('event.details_text') }}
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button id="btn_createGroup" type="submit"
                                    class="btn btn-outline-danger float-right">
                                {{ __('event.notifications_reject') }}
                            </button>
                            <button id="btn_createGroup" type="submit"
                                    class="btn btn-outline-success float-right">
                                {{ __('event.notifications_submit') }}
                            </button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
