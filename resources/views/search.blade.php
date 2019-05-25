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
                @foreach($events as $event)
                    {{ $event->name }}<br>
                @endforeach

            </div>
        </div>
    </div>
@endsection
