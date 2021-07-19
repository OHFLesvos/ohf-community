@extends('layouts.app')

@section('title', __('Create Role'))

@section('content')
    {!! Form::open(['route' => ['roles.store']]) !!}
        <div class="mb-3">
            <div class="form-row">
                <div class="col-md">
                    {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('Name')) }}
                </div>
            </div>
        </div>
        <div class="card-deck mb-3">

            {{-- Users --}}
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Users') }}</div>
                <div class="card-body columns-2">
                    {{ Form::bsCheckboxList('users[]', $users, null) }}
                </div>
            </div>

            {{-- Role administrators --}}
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Role Administrators') }}</div>
                <div class="card-body columns-2">
                    {{ Form::bsCheckboxList('role_admins[]', $users, null) }}
                </div>
            </div>

            {{-- Permissions --}}
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Permissions') }}</div>
                <div class="card-body columns-2">
                    @forelse($permissions as $title => $elements)
                        <div class="column-break-avoid">
                            <h5 @unless($loop->first) class="mt-3" @endunless>{{ $title == null ? __('General') : $title }}</h5>
                            {{ Form::bsCheckboxList('permissions[]', $elements, null) }}
                        </div>
                    @empty
                        <em>{{ __('No permissions defined.') }}</em>
                    @endforelse
                </div>
            </div>

        </div>
        <p class="text-right">
            <x-form.bs-submit-button :label="__('Create')"/>
        </p>
    {!! Form::close() !!}
@endsection
