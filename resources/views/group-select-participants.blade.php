@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('addParticipants') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h2>{{ __('group.participants') }}</h2><br/>
                        </div>
                        {{-- if there are no possible participants, we do not need to show the continue button --}}
                        @if(count($participants) > 0)
                            <div class="col-md-6">
                                <input id="addParticipants" class="btn btn-primary float-right"
                                       type="submit" value="{{ __('group.participants_submit') }}"/>
                            </div>
                        @endif
                    </div>
                    @if ($errors->has('members'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('members') }}
                        </div>
                    @endif
                    <br/>
                    <div class="row">
                        @forelse($participants as $p)

                            <div class="col-md-6">
                                <div class="card-deck mb-3 text-center">
                                    <div class="card mb-4 shadow-sm">
                                        <div class="card-body">
                                            <h3 class="mb-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="same-address-{{$p->id}}" name="members[]"
                                                           value="{{$p->id}}">
                                                    <label class="custom-control-label float-left"
                                                           for="same-address-{{$p->id}}">{{$p->first_name.' '.$p->last_name}}</label>
                                                </div>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{ __('group.no_participants') }}
                        @endforelse
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection