@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('newEvent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('event.created', ['name' => session('newEvent')]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('EventDeleted'))
                    <div class="alert alert-success" role="alert">
                        {{ __('event.deleted', ['name' => session('EventDeleted')]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <h2>{{ __('calendar.title') }}</h2>

                {{-- List all events for this user --}}
                @forelse($events as $e)

                    <div class="card mb-1">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('event.show', $e->id) }}">{{$e->name}}</a></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{ \App\Tools\Date::toUserOutput($e->start_time, 'F j, H:i')}}
                                -
                                {{ \App\Tools\Date::toUserOutput($e->end_time, 'F j, H:i')}}
                            </h6>
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{ $e->location }}
                            </h6>
                            <p class="card-text">{{$e->description}}</p>
                        </div>
                    </div>
                @empty
                    No events found.
                @endforelse

                <br>
                <a id="btn_createEvent" href="{{ route('event.create') }}" role="button"
                   class="btn btn-primary mt-3">{{ __('calendar.create_event')}}</a>
            </div>
        </div>
@endsection
