@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>{{ __('search.title') }}</h2>
                <div class="alert @empty ($results) alert-warning @else alert-primary @endif mt-4" role="alert">
                    {{ trans_choice('search.results', count($results), ['search' => $search, 'results' => count($results)]) }}
                </div>
            </div>
        </div>
    </div>
@endsection
