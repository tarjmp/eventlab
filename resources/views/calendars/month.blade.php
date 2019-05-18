@extends('home')

@section('calendar')

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
            @for($i = 0; $i < 6; $i++)
                <tr>
                    @for($j = 0; $j < 7; $j++)
                        <td style="width:14%">
                            <strong>{{ $i * 7 + $j }}</strong>
                            <br>
                            Test
                        </td>
                    @endfor
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

@endsection