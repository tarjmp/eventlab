@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('show-group-calendar') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h2>{{ __('group.select_public') }}</h2><br>
                        </div>
                        {{-- if there are no possible grooups, we do not need to show the continue button --}}
                        @if(count($groups) > 0)
                            <div class="col-md-6">
                                <input id="addParticipants" class="btn btn-primary btn-sm float-right"
                                       type="submit" value="{{ __('group.participants_submit') }}"/>
                            </div>
                        @endif
                    </div>
                    <br>
                    <div class="row">
                        @forelse($groups as $group)
                            <div class="col-md-6">
                                <div class="card-deck mb-3 text-center">
                                    <div class="card mb-4 shadow-sm">
                                        <div class="card-body">
                                            <h3 class="mb-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="same-address-{{$group->id}}" name="members[]"
                                                           value="{{$group->id}}">
                                                    <label class="custom-control-label float-left"
                                                           for="same-address-{{$group->id}}">{{$group->name}}</label>
                                                </div>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{ __('calendar.no_groups') }}
                        @endforelse
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
