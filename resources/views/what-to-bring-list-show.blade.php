@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @isset($updated)
                    <div class="alert alert-success" role="alert">
                        {{ __('list.updated') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endisset
                <h2>{{ __('list.show_title') }}</h2><br>
                <div>
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
                    </table>
                </div>
                    @if(\App\Tools\PermissionFactory::createEditEvent()->has($eventID))
                        <br>
                        <a id="editEvent" class="btn btn-primary" href="{{ route('listEdit', $eventID) }}">
                            {{ __('list.edit') }}
                        </a>
                    @endif
            </div>
        </div>
    </div>
@endsection
