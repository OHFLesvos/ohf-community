@extends('layouts.app')

@section('title', __('userprofile.title'))

@section('content')

    <div class="card-columns">

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.profile')</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.update']]) !!}
                        <div class="form-group">
                            {{ Form::label('name', __('userprofile.name')) }}
                            {{ Form::text('name', $user->name, [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', __('userprofile.email')) }}
                            {{ Form::text('email', $user->email, [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        {{ Form::bsSubmitButton(__('userprofile.update')) }}

                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.change_password')</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}

                        <div class="form-group">
                            {{ Form::label('old_password', __('userprofile.old_password')) }}
                            {{ Form::password('old_password', [ 'class' => 'form-control'.($errors->has('old_password') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('old_password'))
                                <span class="invalid-feedback">{{ $errors->first('old_password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('password', __('userprofile.new_password')) }}
                            {{ Form::password('password', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('password_confirmation', __('userprofile.confirm_password')) }}
                            {{ Form::password('password_confirmation', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        {{ Form::bsSubmitButton(__('userprofile.update_password')) }}

                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.language')</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach (language()->allowed() as $code => $name)
                            <a href="{{ language()->back($code) }}" class="list-group-item">
                                @if( App::getLocale() == $code )
                                    <span class="text-success">@icon(check)</span>
                                @else
                                    <span class="d-inline-block" style="width: 1em"></span>
                                @endif
                                {{ $name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.account_information')</div>
                <div class="card-body pb-2">
                    <p>@lang('userprofile.account_created_on') <strong>{{ $user->created_at }}</strong> 
                        @lang('userprofile.account_updated_on') <strong>{{ $user->updated_at }}</strong>.</p>
                    @if ( ! $user->roles->isEmpty() )
                        <p>@lang('userprofile.your_roles'):
                            @foreach ($user->roles->sortBy('name') as $role)
                                {{ $role->name }}@if (! $loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.account_removal')</div>
                <div class="card-body">
                    <p>@lang('userprofile.account_remove_desc')</p>
                    {!! Form::open(['route' => ['userprofile.delete'], 'method' => 'delete']) !!}
                        {{ Form::bsDeleteButton(__('userprofile.delete_account'), 'user-times', __('userprofile.delete_confirm')) }}
                    {!! Form::close() !!}
                </div>
            </div>

        </div>

@endsection
