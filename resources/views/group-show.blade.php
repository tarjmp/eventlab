@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h2>{{ $group->name }}</h2>

                @if($group->description != '')
                    <span class="text-muted">{{$group->description}}</span><br>
                @endif

                @if($group->public)
                    <span class="badge badge-primary mt-2 mb-4">{{ __('group.public') }}</span><br>
                @else
                    <span class="badge badge-secondary mt-2 mb-4">{{ __('group.private') }}</span><br>
                @endif


                <div class="form-group">
                    @if($group->public)
                        <div class="row mb-4">
                            <div class="col-md-6">{{ __('group.subscription') }}</div>
                            <div class="col-md-6 text-right">
                                {{ $group->subscribers()->count() }}
                            </div>
                        </div>

                    @endif
                    @if(\App\Tools\PermissionFactory::createShowGroupExtended()->has($group->id))
                        <div class="row mb-4">
                            <div class="col-md-6">{{ __('group.membership') }}</div>
                            <div class="col-md-6 text-right">
                                @foreach($group->members()->orderBy('first_name')->get() as $m)
                                    {{ $m->name() }}<br>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>


                @if(\App\Tools\PermissionFactory::createEditGroup()->has($group->id))
                    <a id="btn_editGroup" class="btn btn-primary btn-sm mt-3"
                       href="{{route('group.edit', $group->id)}}">
                        {{ __('group.edit_button') }}
                    </a>
                @endif

                @if(\App\Tools\PermissionFactory::createSubscribeToGroup()->has($group->id))
                    <form method="POST" action="{{ route('addSubscription', $group->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            {{ __('group.subscribe') }}</button>
                    </form>
                @elseif(\App\Tools\PermissionFactory::createUnsubscribeFromGroup()->has($group->id))
                    <form method="POST" action="{{ route('removeSubscription', $group->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            {{ __('group.unsubscribe') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
