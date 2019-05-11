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
                <div class="row">
                    <div class="col-md-6">
                        <h2>{{ __('group.interface_title') }}</h2><br/>
                    </div>
                    <div class="col-md-6">
                        @if(\App\Tools\PermissionFactory::createCreateGroup()->has())
                            <a id="addGroup" class="btn btn-primary float-right" href="{{ route('participants')}}">
                                {{ __('group.create_group') }}
                            </a>
                        @endif
                    </div>
                </div>
                    <div class="row">
                        @forelse($groups as $g)

                            <div class="col-md-6">

                                <div class="card-deck mb-3 text-center">
                                    <div class="card mb-4 shadow-sm">
                                        <div class="card-body">
                                            <h3 class="mb-0">
                                                <a class="text-dark" href="{{ route('group.show', $g->id) }}">{{$g->name}}</a>
                                            </h3>
                                            <p class="card-text mb-auto">{{$g->description}}</p>
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
@endsection