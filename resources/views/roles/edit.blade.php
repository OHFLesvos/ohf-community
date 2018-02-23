@extends('layouts.app')

@section('title', __('app.edit_role'))

@section('content')

    {!! Form::model($role, ['route' => ['roles.update', $role], 'method' => 'put']) !!}

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">@lang('app.role')</div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">@lang('app.permissions')</div>
                    <div class="card-body">

                        @forelse ($permissions as $k => $v)
                            <label>
                                {{ Form::checkbox('permissions[]', $k, $role->permissions->contains(function ($value, $key) use ($k) { return $value->key == $k; })) }} {{ $v }}
                            </label><br>
                        @empty
                            <em>@lang('app.no_permissions')</em>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
