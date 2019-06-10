@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('list.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <tr>
                            <th> {{ __('list.name') }}</th>
                            <th> {{ __('list.amount') }}</th>
                            <th> {{ __('list.user') }}</th>
                            <th></th>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <td> {{ $item->name }} </td>
                                <td>  {{ $item->amount }} </td>
                                <td>  @if ($item->user) {{ $item->user->name() }} @else <span class="text-muted">{{ __('list.nobody') }}</span> @endif </td>
                                <td>
                                    <form method="POST" action="{{ route('itemEdit', $item->id) }}">
                                        @csrf
                                        <span class="badge badge-primary" style="cursor: pointer;" onclick="$(this).parent().submit();" title="{{ __('list.edit') }}">{{ __('list.editItem') }}</span>
                                    </form>
                                    <form method="POST" action="{{ route('listDelete', $eventID) }}">
                                        @CSRF
                                        <input type="hidden" name="delete" value="true"/>
                                        <input type="hidden" name="item" value="{{ $item->id }}"/>
                                        <span class="badge badge-danger" style="cursor: pointer;" onclick="$(this).parent().submit();" title="{{ __('list.DeleteItem') }}">{{ __('list.Delete') }}</span>
                                    </form>
                                </td>
                                <input id="itemID"
                                       type="hidden"
                                       class="form-control"
                                       name="itemID"
                                       value="{{ $item->id }}">
                            </tr>
                        @endforeach
                        <form method="POST" action="{{ route('listAdd', $eventID) }}">
                            <tr>
                                @csrf
                                <td>
                                    <input id="name"
                                           type="text"
                                           class="form-control"
                                           name="name"
                                           placeholder="{{ __('list.placeholder_name') }}"
                                           required
                                           autofocus>
                                </td>
                                <td>
                                    <input id="amount"
                                           type="text"
                                           class="form-control"
                                           name="amount"
                                           placeholder="{{ __('list.placeholder_amount') }}">
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="on" id="user" name="user">
                                        <label class="form-check-label" for="user">
                                            {{ __('list.assignMe') }}
                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <input id="eventID"
                                       type="hidden"
                                       class="form-control"
                                       value="{{ $eventID }}"
                                       name="eventID">
                                <td colspan="3">
                                    <a class="btn btn-secondary btn-sm float-left" href="{{ route('list', $eventID) }}">
                                        {{ __('list.back') }}
                                    </a>
                                    <button id="btn_submit" type="submit" class="btn btn-primary btn-sm float-right">
                                        {{ __('list.submit') }}
                                    </button>
                                </td>
                            </tr>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
