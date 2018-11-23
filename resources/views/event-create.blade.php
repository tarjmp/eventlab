@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('event.create_title') }}</h2><br/>

                <form method="POST" action="{{ route('event.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('event.name') }}</label>
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                               value="{{ old('name') }}" required autofocus>

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
                                  name="description">{{ old('description') }}</textarea>

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
                               value="{{ old('location') }}">

                        @if ($errors->has('location'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('location') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" name="event-end" type="checkbox" value="" id="all-day-event"
                                   checked>
                            <label class="form-check-label" for="all-day-event">
                                {{ __('event.all_day') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="start-date" class="col-md-4">{{ __('event.start_time') }}</label>
                        <div class="col-md-4 col-6">
                            <input id="start-date" type="date"
                                   class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date"
                                   value="{{ old('date', '2018-11-23') }}" required>
                        </div>
                        <div class="col-md-4 col-6">
                            <input id="start-time" type="time" class="form-control" value="18:00" step="60">
                        </div>
                        @if ($errors->has('date'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('date') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group row" id="end-row">
                        <label for="end-date" class="col-md-4">{{ __('event.end_time') }}</label>
                        <div class="col-md-4 col-6">
                            <input id="end-date" type="date"
                                   class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date"
                                   value="{{ old('date', '2018-11-23') }}">
                        </div>
                        <div class="col-md-4 col-6">
                            <input id="end-time" type="time" class="form-control" value="18:00" step="60">
                        </div>
                        @if ($errors->has('date'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('date') }}</strong>
                            </span>
                        @endif
                    </div>

                    <button id="btn_createEvent" type="submit" class="btn btn-primary">
                        {{ __('event.create_submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
