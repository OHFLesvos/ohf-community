@extends('layouts.app')

@section('title', __('accounting.create_wallet'))

@section('content')

    {!! Form::open(['route' => ['accounting.wallets.store']]) !!}

        <div class="row">
            <div class="col-md-8 mb-4">

                {{-- Common properties --}}
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('name', null, [ 'required', 'autofocus', 'autocomplete' => 'off' ], __('app.name')) }}
                    </div>
                </div>
                <div class="form-row mb-4">
                    <div class="col-md">
                        {{ Form::bsCheckbox('is_default', 1, null, __('app.default')) }}
                    </div>
                </div>

            </div>
            <div class="col-md-4 mb-4">

                {{-- Roles --}}
                @can('viewAny', App\Role::class)
                    <div class="card">
                        <div class="card-header">@lang('app.roles_with_access')</div>
                        <div class="card-body">
                            <p><em>@lang('app.specifying_no_role_will_allow_access_by_any')</em></p>
                            {{ Form::bsCheckboxList('roles[]', $roles->mapWithKeys(fn ($role) => [ $role->id => $role->name ]), null) }}
                            @empty($roles)
                                <em>@lang('app.no_roles_defined')</em>
                            @endempty
                        </div>
                    </div>
                @endcan

            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection
