@extends('home')

@section('calendar')

    <h4 class="row">
        <div class="col-2 text-left">
            <a role="button" class="btn btn-outline-secondary btn-sm"
               href="{{ route('home-day-param', ['year' => $prev['year'], 'month' => $prev['month'], 'day' => $prev['day']]) }}">&laquo;</a>
        </div>
        <div class="col-8 text-center">
            {{ $day }}
        </div>
        <div class="col-2 text-right">
            <a role="button" class="btn btn-outline-secondary btn-sm"
               href="{{ route('home-day-param', ['year' => $next['year'], 'month' => $next['month'], 'day' => $next['day']]) }}">&raquo;</a>
        </div>
    </h4>
    <br>

    {{-- List all events for this user --}}
    @forelse($events as $e)

        <div class="card mb-1">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('event.show', $e->id) }}"
                                          @if($e->myReply() == \App\Event::STATUS_REJECTED) class="text-muted" @endif>{{$e->name}}</a>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    @if($e->all_day)
                        {{ \App\Tools\Date::toUserOutput($e->start_time, 'F j') }},
                        {{  __('event.all_day_small') }}
                    @elseif( \App\Tools\Date::isSameDate($e->start_time, $e->end_time))
                        {{ \App\Tools\Date::toUserOutput($e->start_time, 'F j, H:i')}}
                        -
                        {{ \App\Tools\Date::toUserOutput($e->end_time, 'H:i')}}
                    @else
                        {{ \App\Tools\Date::toUserOutput($e->start_time, 'F j, H:i')}}
                        -
                        {{ \App\Tools\Date::toUserOutput($e->end_time, 'F j, H:i')}}
                    @endif
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $e->location }}
                </h6>
                <p class="card-text">{{$e->description}}</p>
            </div>
        </div>
    @empty
        {{ __('calendar.no_events_day') }}
    @endforelse

@endsection
