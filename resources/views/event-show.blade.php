@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

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

                @if(\App\Tools\PermissionFactory::createShowEvent()->has($event->id))
                    <br>
                    <a id="editEvent" class="btn btn-primary" href="{{ route('event.edit', $event->id) }}">
                        {{ __('event.edit') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
