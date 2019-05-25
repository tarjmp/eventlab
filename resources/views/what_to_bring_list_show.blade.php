@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('list.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <tr>
                            <th> list.name</th>
                            <td> list.amount</td>
                            <td> list.user</td>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <th> {{ $item->name }} </th>
                                <td>  {{ $item->amount }} </td>
                                <td>  {{ $item->user_id }} </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
