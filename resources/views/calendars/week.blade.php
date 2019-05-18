@extends('home')

@section('calendar')
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th scope="col"></th>
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
            @for($i = 0; $i < 24; $i+= 2)
                <tr>
                    <th scope="row">{{ $i . ':00' }}</th>
                    <td style="width:13%"> </td>
                    <td style="width:13%"> </td>
                    <td style="width:13%"> </td>
                    <td style="width:13%"> </td>
                    <td style="width:13%"> </td>
                    <td style="width:13%"> </td>
                    <td style="width:13%"> </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
@endsection