@extends('home')

@section('calendar')

    <h4 class="row">
        <div class="col-2 text-left">
            <a role="button" class="btn btn-outline-secondary btn-sm" href="{{ route('home-month-param', ['year' => $prev['year'], 'month' => $prev['month']]) }}">&laquo;</a>
        </div>
        <div class="col-8 text-center">
            {{ $month }}
        </div>
        <div class="col-2 text-right">
            <a role="button" class="btn btn-outline-secondary btn-sm" href="{{ route('home-month-param', ['year' => $next['year'], 'month' => $next['month']]) }}">&raquo;</a>
        </div>
    </h4>
    <br>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">{{ __('calendar.monday') }}</th>
                <th scope="col">{{ __('calendar.tuesday') }}</th>
                <th scope="col">{{ __('calendar.wednesday') }}</th>
                <th scope="col">{{ __('calendar.thursday') }}</th>
                <th scope="col">{{ __('calendar.friday') }}</th>
                <th scope="col">{{ __('calendar.saturday') }}</th>
                <th scope="col">{{ __('calendar.sunday') }}</th>

            </tr>
            </thead>
            <tbody>
                <tr>
                    {{-- fill all empty days before the begin of the month --}}
                    @for($k = 1; $k < $days[1]['dayOfWeek']; $k++)
                        <td style="width:14%"></td>
                    @endfor

                    {{-- fill the actual days --}}
                    @for($i = 1; $i <= count($days); $i++)
                        <td style="width:14%; cursor:pointer;" onclick="window.location.href='{{ route('home-day-param', ['year' => $date['year'], 'month' => $date['month'], 'day' => $i]) }}';">
                            <strong>{{ $i }}</strong>
                            <br>
                            @foreach($days[$i]['events'] as $event)
                                <a href="{{ route('event.show', $event['id']) }}" @if($event['status'] == \App\Event::STATUS_REJECTED) class="text-muted" @endif>{{ $event['name'] }}</a><br>
                            @endforeach
                        </td>

                        {{-- line break after sunday --}}
                        @if ($days[$i]['dayOfWeek'] == 7)
                            </tr><tr>
                        @endif
                    @endfor

                    {{-- fill all empty days at the end of the month --}}
                    @for($k = $days[count($days)]['dayOfWeek'] + 1; $k <= 7; $k++)
                        <td style="width:14%"></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>

@endsection