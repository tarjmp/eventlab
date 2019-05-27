@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('list.show_title') }}</h2><br>
                <div>
                    <form method="POST" action="{{ route('listStore', $eventID) }}">
                        @csrf
                        <table class="table table-striped table-hover table-reflow">
                            <tr>
                                <th> {{ __('list.name') }}</th>
                                <th> {{ __('list.amount') }}</th>
                                <th></th>
                                <th> {{ __('list.user') }}</th>
                            </tr>
                            @foreach($items as $item)
                                <tr>
                                    <td> {{ $item->name }} </td>
                                    <td>  {{ $item->amount }} </td>
                                    <td>
                                        @if(isset($item->full_name)) &#x2611;
                                        @else &#x2610;
                                        @endif </td>
                                    <td>  {{ $item->full_name }} </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <input id="name"
                                           type="text"
                                           class="form-control"
                                           name="name"
                                           required>
                                </td>
                                <td>
                                    <input id="amount"
                                           type="text"
                                           class="form-control"
                                           name="amount"
                                           required>
                                </td>
                                <td>
                                    <input id="user"
                                           type="checkbox"
                                           class="form-control"
                                           name="user"></td>
                                <td> {{ __('list.assignMe') }}</td>
                            </tr>
                        </table>
                        <input id="eventID"
                               type="hidden"
                               class="form-control"
                               value="{{ $eventID }}"
                               name="eventID">
                        <button id="btn_submit" type="submit" class="btn btn-primary">
                            {{ __('list.submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
