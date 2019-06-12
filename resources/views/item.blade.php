@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('item.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <form method="POST" action="{{ route('itemSave', $item->id) }}">
                            @csrf
                            <tr>
                                <td> {{ __('item.item') }} </td>
                                <td><input id="name"
                                           type="text"
                                           class="form-control"
                                           name="name"
                                           value="{{ $item->name }}"
                                           required
                                           autofocus></td>
                            </tr>
                            <tr>
                                <td>  {{ __('list.amount') }} </td>
                                <td><input id="amount"
                                           type="text"
                                           class="form-control"
                                           name="amount"
                                           value="{{$item->amount}}"
                                           placeholder="{{ __('list.placeholder_amount') }}"></td>
                            </tr>
                            <tr>
                                <input id="eventID"
                                       type="hidden"
                                       class="form-control"
                                       value="{{ $item->event_id }}"
                                       name="event_id">
                                <td colspan="2">
                                    <a class="btn btn-secondary btn-sm float-left" href="{{ route('listEdit', $item->event_id) }}">
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
