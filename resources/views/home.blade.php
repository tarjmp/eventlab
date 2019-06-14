@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                @if (session('newEvent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('event.created', ['name' => session('newEvent')]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('EventDeleted'))
                    <div class="alert alert-danger" role="alert">
                        {{ __('event.deleted', ['name' => session('EventDeleted')]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('GroupLeft'))
                    <div class="alert alert-danger" role="alert">
                        {{ __('group.left', ['name' => session('GroupLeft')]) }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <h2>@auth{{ __('calendar.title') }}@else{{ \App\Group::findOrFail(session('public_group'))->name }}@endauth</h2>
                <br>
                <div class="row">
                    <div class="col-6">
                        <div class="btn-group" role="group">
                            <a role="button"
                               class="btn btn-sm @if ($type == \App\Http\Controllers\HomeController::TYPE_MONTH) btn-primary @else btn-outline-primary @endif"
                               href="{{ route('home-month') }}">{{ __('calendar.month') }}</a>
                            <a role="button"
                               class="btn btn-sm @if ($type == \App\Http\Controllers\HomeController::TYPE_DAY)   btn-primary @else btn-outline-primary @endif"
                               href="{{ route('home-day'  ) }}">{{ __('calendar.day') }}</a>
                            <a role="button"
                               class="btn btn-sm @if ($type == \App\Http\Controllers\HomeController::TYPE_NEXT)  btn-primary @else btn-outline-primary @endif"
                               href="{{ route('home-next' ) }}">{{ __('calendar.next') }}</a>
                        </div>
                    </div>
                    <div class="col-6">
                        @auth
                            <a id="btn_createEvent" href="{{ route('event.create') }}" role="button"
                               class="btn btn-primary float-right">{{ __('calendar.create_event')}}</a>
                        @endauth
                    </div>
                </div>
                <br><br>

                {{-- Print the contents of the actual calendar view - depending on the user's selection --}}
                @yield('calendar')

                @auth
                    <form method="POST" action="{{ route('toggle-rejected') }}" id="toggle-rejected-form">
                        @csrf
                        <div class="form-check mt-3 text-right">
                            <input class="form-check-input" type="checkbox" value="" id="display-rejected"
                                   onchange="this.form.submit();"
                                   @if(session(\App\Http\Controllers\HomeController::SHOW_REJECTED_EVENTS)) checked @endif>
                            <label class="form-check-label text-muted" for="display-rejected">
                                {{ __('calendar.show-rejected') }}
                            </label>
                        </div>
                    </form>
                @endauth
            </div>
        </div>
    </div>
@endsection
