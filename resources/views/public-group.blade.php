@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    @forelse($groups as $group)
                        <form method="POST" action="{{ route('show-group-calendar') }}">
                            @csrf
                            <div class="form-control">
                                <h3 class=" mb-0">
                            <span class="badge badge-primary" style="cursor: pointer;"
                                  onclick="$(this).parent().parent().parent().submit();"
                                  title="{{ $group->name }}">{{ $group->name }}</span>
                                </h3>
                            </div>
                            <br>
                            <input class="form-control" id="id" type="hidden" value="{{ $group->name }}" name="id">
                        </form>
                    @empty
                        {{ __('calendar.no_groups') }}
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
