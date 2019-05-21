@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('registration.title') }}</h2><br>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="first_name">{{ __('user.first_name') }}*</label>
                            <input id="first_name" type="text"
                                   class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                   name="first_name" value="{{ old('first_name') }}" required autofocus>

                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">{{ __('user.last_name') }}*</label>
                            <input id="last_name" type="text"
                                   class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                   name="last_name" value="{{ old('last_name') }}" required>

                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('user.email') }}*</label>
                        <input id="email" type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                               value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="location">{{ __('user.location') }}</label>
                        <input id="location" type="text"
                               class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location"
                               value="{{ old('location') }}">

                        @if ($errors->has('location'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">{{ __('user.date_of_birth') }}</label>
                        <input id="date_of_birth" type="date"
                               class="form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}"
                               name="date_of_birth" value="{{ old('date_of_birth') }}">

                        @if ($errors->has('date_of_birth'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('date_of_birth') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">{{ __('user.password') }}*</label>
                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password-confirm">{{ __('user.password_confirm') }}*</label>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required>
                        </div>
                    </div>

                    <button id="btn_register" type="submit" class="btn btn-primary">
                        {{ __('registration.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
