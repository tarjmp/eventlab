@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('subscriptions.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <tr>
                            <th>{{ __('subscriptions.name') }}</th>
                            <th>{{ __('subscriptions.description') }}</th>
                            <th>{{ __('subscriptions.unsubscribe') }}</th>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <td> {{ $item->name }} </td>
                                <td> {{ $item->description }} </td>
                                <td>
                                    <form method="POST" action="{{ route('UpdateSubscriptions') }}">
                                        @csrf
                                        <input id="groupID"
                                               type="hidden"
                                               class="form-control"
                                               name="groupID"
                                               value="{{ $item->id }}">
                                        <button id="btn_submit" type="submit"
                                                class="btn btn-primary">{{ __('subscriptions.unsubscribe') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
