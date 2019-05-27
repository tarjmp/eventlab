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
                            <th> {{ __('list.alreadyBrought') }}</th>
                            <th> {{ __('list.user') }}</th>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <td> {{ $item->name }} </td>
                                <td>  {{ $item->amount }} </td>
                                <td>
                                    @if(isset($item->full_name))
                                        <div class="form-check">
                                            <input id="alreadyBrought"
                                                   type="checkbox"
                                                   class="form-check-input"
                                                   value=""
                                                   name="alreadyBrought"
                                                   checked>
                                        </div>
                                    @else
                                        <div class="form-check">
                                            <input id="alreadyBrought"
                                                   type="checkbox"
                                                   class="form-check-input"
                                                   value=""
                                                   name="alreadyBrought">
                                        </div> @endif </td>
                                <td>  {{ $item->full_name }} </td>
                                <input id="itemID"
                                       type="hidden"
                                       class="form-control"
                                       name="itemID"
                                       value="{{ $item->id }}">
                            </tr>
                        @endforeach
                        <form method="POST" action="{{ route('listStore', $eventID) }}">
                            <tr>
                                @csrf
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
                                    <div class="form-check">
                                        <input id="user"
                                               type="checkbox"
                                               class="form-check-input"
                                               value=""
                                               name="user"></div>
                                </td>
                                <td> {{ __('list.assignMe') }}</td>
                            </tr>
                            <tr>
                                <input id="eventID"
                                       type="hidden"
                                       class="form-control"
                                       value="{{ $eventID }}"
                                       name="eventID">
                                <td colspan="4">
                                    <button id="btn_submit" type="submit" class="btn btn-primary">
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
