@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @isset($updated)
                    <div class="alert alert-success" role="alert">
                        {{ __('event.updated') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endisset

                <h2>{{ __('event.update_title') }}</h2><br/>

                <form method="POST" action="{{ route('event.update', $id) }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('event.name') }}</label>
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                               value="{{$event->name}}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('event.description') }}</label>

                        <textarea id="description"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                  name="description">{{$event->description}}</textarea>

                        @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="location">{{ __('event.location') }}</label>
                        <input id="location" type="text"
                               class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location"
                               value="{{$event->location}}">

                        @if ($errors->has('location'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('location') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" name="all-day-event" type="checkbox" value="all-day-event"
                                   id="all-day-event"
                                {{ $event->all_day ? 'checked' : '' }}>
                            <label class="form-check-label" for="all-day-event">
                                {{ __('event.all_day') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="start-date" class="col-md-4">{{ __('event.start_time') }}</label>
                        <div class="col-md-4 col-6">
                            <input id="start-date" type="date"
                                   class="form-control{{ $errors->has('start-date') ? ' is-invalid' : '' }}"
                                   name="start-date"
                                   value="{{ $start_date }}" required>
                            @if ($errors->has('start-date'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('start-date') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4 col-6">
                            <input id="start-time" name="start-time" type="time"
                                   class="form-control {{ $errors->has('start-time') ? ' is-invalid' : '' }}"
                                   value="{{ $start_time }}" step="60">
                            @if ($errors->has('start-time'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('start-time') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>

                    <div class="form-group row" id="end-row">
                        <label for="end-date" class="col-md-4">{{ __('event.end_time') }}</label>
                        <div class="col-md-4 col-6">
                            <input id="end-date" type="date"
                                   class="form-control{{ $errors->has('end-date') ? ' is-invalid' : '' }}"
                                   name="end-date"
                                   value="{{ $end_date }}">
                            @if ($errors->has('end-date'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('end-date') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4 col-6">
                            <input id="end-time" name="end-time" type="time"
                                   class="form-control {{ $errors->has('end-time') ? ' is-invalid' : '' }}"
                                   value="{{ $end_time }}" step="60">
                            @if ($errors->has('end-time'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('end-time') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <button id="btn_updateEvent" type="submit" class="btn btn-primary">
                        {{ __('event.update_submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
