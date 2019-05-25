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
                    <div class="card mb-2">
                        <div class="card-body p-2 px-4 pt-3">
                            <a href="{{ route('group.show', $group->id) }}"><h5 class="card-title">{{ $group->name }}</h5></a>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $group->description }}</h6>
                        </div>
                    </div>
                @endforeach

                @if(count($events) > 0)
                    <h4 class="my-4">{{ __('search.events') }}</h4>
                @endif
                @foreach($events as $event)
                    <div class="card mb-2">
                        <div class="card-body p-2 px-4 pt-3">
                            <a href="{{ route('event.show', $event->id) }}"><h5 class="card-title">{{ $event->name }}</h5></a>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $event->description }}</h6>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
