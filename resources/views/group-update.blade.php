@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('group.update_title') }}</h2><br/>

               <form method="POST" action="{{ route('group.update', $id) }}">
                        @csrf
                   @method('PUT')

                    <div class="form-group">
                        <label for="name">{{ __('group.name') }}</label>
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                               value="{{ old('name', $group->name) }}" required autofocus>

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
                                  name="description">{{ old('description', $group->description) }}</textarea>

                        @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input id="public" name="privacy" value="public" type="radio" class="custom-control-input" {{ $group->public ? 'checked' : '' }} disabled required>
                                <label class="custom-control-label" for="public">{{ __('group.public') }}</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input id="private" name="privacy" value="private" type="radio" class="custom-control-input" {{ !$group->public ? 'checked' : '' }} disabled required>
                                <label class="custom-control-label" for="private">{{ __('group.private') }}</label>
                            </div>
                    </div>

                    <input id="btn_createGroup" type="submit" class="btn btn-primary" value="{{ __('group.update_submit') }}"/>

                </form>
                <br/>
                @if (\App\Tools\Permission::has(\App\Tools\Permission::leaveGroup, $id))
                    <form method="POST" action="{{ route('leave-group', $id) }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$id}}"/>
                        <button id="btn_leaveGroup" type="submit" class="btn btn-danger">
                            {{ __('group.leave_submit') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection