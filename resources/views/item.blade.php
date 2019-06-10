@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('item.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <form method="POST" action="{{ route('itemSave', $item->id) }}">
                            <tr>
                                @csrf
                                <th> {{ __('item.name') }}</th>
                                <th> {{ __('item.value') }}</th>
                            </tr>
                            <tr>
                                <td> {{ __('item.name') }} </td>
                                <td><input id="name"
                                           type="text"
                                           class="form-control"
                                           name="name"
                                           value="{{ $item->name }}"
                                           required
                                           autofocus></td>
                            </tr>
                            <tr>
                                <td>  {{ __('item.amount') }} </td>
                                <td><input id="amount"
                                           type="text"
                                           class="form-control"
                                           name="amount"
                                           value="{{$item->amount}}"
                                           placeholder="{{ __('item.placeholder_amount') }}"></td>
                            </tr>
                            <tr>
                                <input id="eventID"
                                       type="hidden"
                                       class="form-control"
                                       value="{{ $item->eventID }}"
                                       name="eventID">
                                <td colspan="3">
                                    <a class="btn btn-secondary btn-sm float-left" href="{{ route('list', $item->event_id) }}">
                                        {{ __('item.back') }}
                                    </a>
                                    <button id="btn_submit" type="submit" class="btn btn-primary btn-sm float-right">
                                        {{ __('item.submit') }}
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
