@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('item-added'))
                    <div class="alert alert-success" role="alert">
                        {{ __('list.updated') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <h2>{{ __('list.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <tr>
                            <th> {{ __('list.name') }}</th>
                            <th> {{ __('list.amount') }}</th>
                            <th> {{ __('list.user') }}</th>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <td> {{ $item->name }} </td>
                                <td>  {{ $item->amount }} </td>
                                <td>
                                    @if(isset($item->user))
                                        {{ $item->user->name() }}
                                        @if($item->user->id == Auth::id())
                                            <form method="POST" action="{{ route('listBring', $eventID) }}" class="d-inline">
                                                @CSRF
                                                <input type="hidden" name="item" value="{{ $item->id }}"/>
                                                &emsp;
                                                <span class="font-weight-bold" style="cursor: pointer;" onclick="$(this).parent().submit();" title="{{ __('list.unassign_me') }}">&times;</span>
                                            </form>
                                        @endif
                                    @else
                                        <form method="POST" action="{{ route('listBring', $eventID) }}">
                                            @CSRF
                                            <input type="hidden" name="user" value="user"/>
                                            <input type="hidden" name="item" value="{{ $item->id }}"/>
                                            <span class="badge badge-primary" style="cursor: pointer;" onclick="$(this).parent().submit();" title="{{ __('list.assign_me') }}">{{ __('list.nobody') }}</span>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                    @if(\App\Tools\PermissionFactory::createEditEvent()->has($eventID))
                        <br>
                        <a id="editEvent" class="btn btn-primary btn-sm" href="{{ route('listEdit', $eventID) }}">
                            {{ __('list.edit') }}
                        </a>
                    @endif
            </div>
        </div>
    </div>
@endsection
