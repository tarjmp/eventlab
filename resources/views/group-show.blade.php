@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('group.show_title') }}</h2><br>

                <div class="form-group">
                    <label for="name">{{ __('group.name') }}</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{$group->name}}" readonly>

                </div>

                <div class="form-group">
                    <label for="description">{{ __('group.description') }}</label>

                    <textarea id="description" class="form-control" name="description"
                              readonly>{{$group->description}}</textarea>
                </div>


                <div class="form-group">
                    <div class="custom-control custom-radio">
                        <input id="public" name="privacy" value="public" type="radio" class="custom-control-input"
                               @if($group->public) checked @endif required disabled>
                        <label class="custom-control-label" for="public">{{ __('group.public') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="private" name="privacy" value="private" type="radio" class="custom-control-input"
                               @if(!$group->public) checked @endif required disabled>
                        <label class="custom-control-label" for="private">{{ __('group.private') }}</label>
                    </div>
                </div>

                <div class="form-group">
                    @if($group->public)
                        <label for="subscribers">{{ __('group.subscription',  ['subscribers' => $numberSubscriptions]) }}</label>
                        <br>
                    @endif
                    @if(\App\Tools\PermissionFactory::createShowGroupExtended()->has($group->id))
                        <label for="members">{{ __('group.membership') }}</label>
                        <ul>
                            @foreach($group->members()->orderBy('first_name')->get() as $m)
                                <li>
                                    {{$m->first_name.' '.$m->last_name}}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>


                @if(\App\Tools\PermissionFactory::createEditGroup()->has($group->id))
                    <a id="btn_editGroup" class="btn btn-primary" href="{{route('group.edit', $group->id)}}">
                        {{ __('group.edit_button') }}
                    </a>
                @endif

                @if(!$group->subscribed())
                    <form method="POST" action="{{ route('AddSubscription') }}">
                        @csrf
                        <br>
                        <input id="groupID"
                               type="hidden"
                               class="form-control"
                               value="{{ $group->id }}"
                               name="groupID">
                        <button id="btn_subscribe" type="submit" class="btn btn-primary">
                            {{ __('group.subscribe') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
