@extends('layouts.app')

@section('title', __('app.manage_members'))

@section('content')

    {!! Form::model($role, ['route' => ['roles.updateMembers', $role], 'method' => 'put']) !!}

        <div class="mb-3">
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('name', null, [ 'readonly' ], __('app.name')) }}
                </div>
            </div>
        </div>

        <div class="card-deck mb-3">

            {{-- Users --}}
            <div class="card">
                <div class="card-header">@lang('app.users')</div>
                <div class="card-body columns-3">
                    {{ Form::bsCheckboxList('users[]', $users, $role->users()->orderBy('name')->get()->pluck('name', 'id')->keys()->toArray()) }}
                </div>
            </div>

        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
