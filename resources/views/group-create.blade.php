@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('group.create_title') }}</h2><br>

                <form method="POST" action="{{ route('group.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('group.name') }}</label>
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                               value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('group.description') }}</label>

                        <textarea id="description"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                  name="description">{{ old('description') }}</textarea>

                        @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input id="public" name="privacy" value="public" type="radio" class="custom-control-input"
                                   required>
                            <label class="custom-control-label" for="public">{{ __('group.public') }}</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="private" name="privacy" value="private" type="radio" class="custom-control-input"
                                   checked required>
                            <label class="custom-control-label" for="private">{{ __('group.private') }}</label>
                        </div>
                    </div>
                    <input type="hidden" name="members" value="{{session('members')}}"/>


                    <button id="btn_createGroup" type="submit" class="btn btn-primary">
                        {{ __('group.create_submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection