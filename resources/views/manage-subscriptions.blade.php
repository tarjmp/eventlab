@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('subscriptions.show_title') }}</h2><br>
                <div>
                    @if($groups->count())
                        <table class="table table-striped table-hover table-reflow">
                            <tr>
                                <th>{{ __('subscriptions.name') }}</th>
                                <th>{{ __('subscriptions.description') }}</th>
                                <th></th>
                            </tr>
                            @foreach($groups as $group)
                                <tr>
                                    <td> {{ $group->name }} </td>
                                    <td> {{ $group->description }} </td>
                                    <td style="vertical-align: middle">
                                        <form method="POST" action="{{ route('removeSubscription', $group->id) }}">
                                            @csrf
                                            <button id="btn_submit" type="submit"
                                                    class="btn btn-primary btn-sm">{{ __('subscriptions.unsubscribe') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <h5>{{ __('subscriptions.no_subscriptions') }}</h5>
                        <br>
                        {{ __('subscriptions.no_subscriptions_info') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
