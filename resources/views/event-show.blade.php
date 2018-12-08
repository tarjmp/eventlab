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

                <h2>{{ __('event.show_title') }}</h2><br/>

                <div class="form-group">
                    <label for="name">{{ __('event.name') }}</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{$event->name}}" readonly>
                </div>

                <div class="form-group">
                    <label for="description">{{ __('event.description') }}</label>
                    <textarea id="description" class="form-control" name="description"
                              readonly>{{$event->description}}</textarea>
                </div>

                <div class="form-group">
                    <label for="location">{{ __('event.location') }}</label>
                    <input id="location" type="text" class="form-control" name="location" value="{{$event->location}}"
                           readonly>
                </div>
                @if($event->all_day)
                    <div class="form-group">
                        <label for="all-day-event">{{ __('event.all_day') }}</label>
                    </div>
                @endif
                <div class="form-group row">
                    <label for="start-date" class="col-md-4">{{ __('event.start_time') }}</label>
                    <div class="col-md-4 col-6">
                        <input id="start-date" type="date" class="form-control" name="start-date"
                               value="{{ $start->date() }}" readonly>
                    </div>
                    <div class="col-md-4 col-6">
                        <input id="start-time" name="start-time" type="time" class="form-control"
                               value="{{ $start->time() }}" step="60" readonly>
                    </div>
                </div>

                <div class="form-group row" id="end-row">
                    <label for="end-date" class="col-md-4">{{ __('event.end_time') }}</label>
                    <div class="col-md-4 col-6">
                        <input id="end-date" type="date" class="form-control" name="end-date" value="{{ $end->date() }}"
                               readonly>
                    </div>
                    <div class="col-md-4 col-6">
                        <input id="end-time" name="end-time" type="time" class="form-control" value="{{ $end->time() }}"
                               step="60" readonly>
                    </div>
                </div>

                @if(\App\Tools\Permission::has(\App\Tools\Permission::showEvent, $event->id))
                    <a id="editEvent" class="btn btn-primary" href="{{ route('event.edit', $event->id) }}">
                        {{ __('event.edit') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
