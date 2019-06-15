@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('newGroup'))
                    <div class="alert alert-success" role="alert">
                        {{ __('group.created', ['name' => session('newGroup')]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @auth
                    <div class="row">
                        <div class="col-6">
                            <h2>{{ __('group.interface_title') }}</h2><br>
                        </div>
                        <div class="col-6">
                            @if(\App\Tools\PermissionFactory::createCreateGroup()->has())
                                <a id="addGroup" class="btn btn-primary float-right" href="{{ route('participants')}}">
                                    {{ __('group.create_group') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        @forelse($groups as $g)
                            <div class="col-12">
                                <div class="card-deck mb-1">
                                    <div class="card mb-1 shadow-sm">
                                        <div class="card-body">
                                            <h4 class="mb-0 d-inline">
                                                <a href="{{ route('group.show', $g->id) }}">{{$g->name}}</a>
                                            </h4>
                                            @if($g->public)
                                                <span class="badge badge-light mt-2 mb-4 float-right">{{ __('group.public') }}</span>
                                                <br>
                                            @else
                                                <span class="badge badge-warning mt-2 mb-4 float-right">{{ __('group.private') }}</span>
                                                <br>
                                            @endif
                                            <p class="card-text mb-auto">{{$g->description}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{ __('group.no_groups') }}
                        @endforelse
                    </div>
                @endauth

                <div class="row">
                    <div class="col-12">
                        <h2 class="mt-3 mb-4">{{ __('group.public_groups') }}</h2>
                    </div>
                </div>

                <div class="row">
                    @forelse($publicGroups as $g)
                        <div class="col-12">
                            <div class="card-deck mb-1">
                                <div class="card mb-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="mb-0 d-inline">
                                                    <a href="{{ route('group.show', $g->id) }}">{{$g->name}}</a>
                                                </h4>
                                                <p class="card-text mb-auto">{{$g->description}}</p>
                                            </div>
                                            <div class="col-md-4">
                                                @if(\App\Tools\PermissionFactory::createSubscribeToGroup()->has($g->id))
                                                    <form method="POST" action="{{ route('addSubscription', $g->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-primary btn-sm float-right">
                                                            {{ __('group.subscribe') }}</button>
                                                    </form>
                                                @elseif(\App\Tools\PermissionFactory::createUnsubscribeFromGroup()->has($g->id))
                                                    <form method="POST"
                                                          action="{{ route('removeSubscription', $g->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-secondary btn-sm float-right">
                                                            {{ __('group.unsubscribe') }}</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('GuestSelect') }}">
                                                        @csrf
                                                        <input type="hidden" name="public_group" id="public_group"
                                                               value="{{ $g->id }}">
                                                        <button type="submit"
                                                                class="btn btn-secondary btn-sm float-right">
                                                            {{ __('group.show') }}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{ __('group.no_groups') }}
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection
