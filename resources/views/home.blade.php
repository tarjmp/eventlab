@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('newEvent'))
                <div class="alert alert-success" role="alert">
                    {{ __('event.created', ['name' => session('newEvent')]) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">{{ __('calendar.title') }}</div>

                <div class="card-body">
                    This will be the user calendar page.<br/><br/>
                    <a id="btn_createEvent" href="{{ route('event.create') }}" role="button" class="btn btn-primary">{{ __('calendar.create_event')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
