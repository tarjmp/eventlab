@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>{{ __('search.title') }}</h2>
                <div class="alert @if ($num_results == 0) alert-warning @else alert-primary @endif mt-4" role="alert">
                    {{-- use str_replace as trans_choice does not handle user input with a pipe correctly (it interprets the pipe as one choice in the language text) --}}
                    {{ str_replace(':search', $search, trans_choice('search.results', $num_results, ['num_results' => $num_results])) }}
                </div>

                @if(count($groups) > 0)
                    <h4 class="my-4">{{ __('search.groups') }}</h4>
                @endif
                @foreach($groups as $group)
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
                @endforeach

                @if(count($events) > 0)
                    <h4 class="my-4">{{ __('search.events') }}</h4>
                @endif
                @foreach($events as $event)
                    <div class="col-12">
                        <div class="card-deck mb-1">
                            <div class="card mb-1 shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="mb-0 d-inline">
                                                <a href="{{ route('event.show', $event->id) }}">{{$event->name}}</a>
                                            </h4>
                                            <p class="card-text mb-auto">{{$event->description}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
