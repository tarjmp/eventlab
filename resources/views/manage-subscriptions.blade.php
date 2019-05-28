@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('subscriptions.show_title') }}</h2><br>
                <div>
                    @forelse($groups as $group)
                        <div class="col-12">
                            <div class="card-deck mb-1">
                                <div class="card mb-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="mb-0 d-inline">
                                                    <a href="{{ route('group.show', $group->id) }}">{{$group->name}}</a>
                                                </h4>
                                                <p class="card-text mb-auto">{{$group->description}}</p>
                                            </div>
                                            <div class="col-md-4">
                                                @if(\App\Tools\PermissionFactory::createSubscribeToGroup()->has($group->id))
                                                    <form method="POST" action="{{ route('addSubscription', $group->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-primary btn-sm float-right">
                                                            {{ __('group.subscribe') }}</button>
                                                    </form>
                                                @elseif(\App\Tools\PermissionFactory::createUnsubscribeFromGroup()->has($group->id))
                                                    <form method="POST"
                                                          action="{{ route('removeSubscription', $group->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-secondary btn-sm float-right">
                                                            {{ __('group.unsubscribe') }}</button>
                                                    </form>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h5>{{ __('subscriptions.no_subscriptions') }}</h5>
                        <br>
                        {{ __('subscriptions.no_subscriptions_info') }}
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
